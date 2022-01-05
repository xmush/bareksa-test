<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleTag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

class ArticleController extends Controller
{
    
    public function index(Request $request) {
        $key = $request->fullUrl();

        $validation = Validator::make($request->all(), [
            'topic' => 'nullable|string',
            'status' => 'nullable|string|in:draft,deleted,publish'
        ]);

        if ($validation->fails()) {
            return response()->json(['message'=> $validation->errors()->first(), 'data' => []], 400);
        }

        $filter_topic = $request->filled('topic') ? $request->topic : '';

        if ($request->filled('status')) {
            $news = Article::where('status', $request->status);
        } else {
            $news = Article::whereIn('status', ['draft', 'deleted', 'publish']);
        }

        if ($request->filled('topic')) {
            $news = $news->with([
                'topic',
                'tags' => function($query) {
                    $query->with('tag');
                }
            ])
            ->whereHas('topic', function($q) use ($filter_topic) {
                $q->where('name', 'LIKE', "%{$filter_topic}%");
            })->get();

        } else {
            $news = $news->with([
                'topic',
                'tags' => function($query) {
                    $query->with('tag');
                }
            ])->get();
        }

        Redis::setex($key, 1800, $news);
        
        return response()->json(['message' => 'success','data' => $news], 200);

    }


    public function detail(Request$request, $news_id) {
        $key = $request->fullUrl();

        $article = Article::where('id', $news_id)
            ->where('status', '!=', 'deleted')
            ->with(['topic', 'tags' => function($query) {
                $query->with('tag');
            }])
            ->first();

        if (is_null($article)) {
            return response()->json(['message'=> 'Not Found!', 'data' => []], 404);
        }

        Redis::setex($key, 1800, $article);
        
        return response()->json(['message' => 'success', 'data' => $article], 200);
    }


    public function update(Request $request, $news_id) {

        $news = Article::where('id', $news_id)->where('status', '!=', 'deleted')->first();

        if (is_null($news)) {
            return response()->json(['message' => 'Not Found!', 'data' => []], 404);
        }

        $validation = Validator::make($request->all(), [
            'title' => 'nullable|max:100|unique:articles,title,'.$news_id.',id',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:draft,publish'
        ]);

        if ($validation->fails()) {
            return response()->json(['message'=> $validation->errors()->first(), 'data' => []], 400);
        }

        try {

            if ($request->filled('title')) {
                $news->title = $request->title;
            }

            if ($request->filled('description')) {
                $news->description = $request->description;
            }

            if ($request->filled('status')) {
                $news->status = $request->status;
            }

            $news->save();

            return response()->json(['message'=> 'Data updated', 'data' => $news], 200);
            
        } catch (Exception $e) {

            return response()->json(['message'=> $e->getMessage(), 'data' => []], 500);

        }   

    }


    public function create(Request $request) {
        $validation = Validator::make($request->all(), [
            'title' => 'required|max:100|unique:articles,title',
            'description' => 'required|string|max:1000',
            'topic_id' => 'required|numeric|exists:topics,id',
            'tags_id' => 'nullable|array|',
            'tags_id.*' => 'required|string|distinct|exists:tags,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['message'=> $validation->errors()->first(), 'data' => []], 400);
        }

        DB::beginTransaction();

        try {           
            $news = Article::create([
                'topic_id' => $request->topic_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'publish'
            ]);

            foreach ($request->tags_id as $tag_id) {
                ArticleTag::create([
                    'article_id' => $news->id,
                    'tag_id' => $tag_id
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Success', 'data' => $news], 200);
            
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message'=> $e->getMessage(), 'data' => []], 500);

        }
    }


    public function delete($news_id) {
        $news = Article::where('id', $news_id)->where('status', '!=', 'deleted')->first();

        if (is_null($news)) {
            return response()->json(['message' => 'Not Found!', 'data' => []], 404);
        }

        try {
            $news->status = 'deleted';
            $news->save();

            return response()->json(['message'=> 'Data deleted', 'data' => []], 200);
            
        } catch (Exception $e) {

            return response()->json(['message'=> $e->getMessage(), 'data' => []], 500);

        }        
    }

}
