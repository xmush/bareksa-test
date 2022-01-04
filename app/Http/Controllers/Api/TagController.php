<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{

    public function index(Request $request) {
        if ($request->has('status')) {
            $tag = Tag::where('status', $request->status)->get();
        } else {
            $tag = Tag::all();
        }
        
        return response()->json(['message' => 'success','data' => $tag], 200);
    }

    
    public function detail($tag_id) {
        $tag = Tag::where('id', $tag_id)->where('status', '!=', 'deleted')->first();

        if (is_null($tag)) {
            return response()->json(['message'=> 'Not Found!', 'data' => []], 404);
        }
        
        return response()->json(['message' => 'success', 'data' => $tag], 200);
    }


    public function update(Request $request, $tag_id) {
        $tag = Tag::where('id', $tag_id)->where('status', '!=', 'deleted')->first();

        if (is_null($tag)) {
            return response()->json(['message' => 'Not Found!', 'data' => []], 404);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'nullable|max:20|unique:tags,name,'.$tag_id.',id',
            'status' => 'nullable|string|in:draft,publish'
        ]);

        if ($validation->fails()) {
            return response()->json(['message'=> $validation->errors()->first(), 'data' => []], 400);
        }

        try {

            if ($request->filled('name')) {
                $tag->name = $request->name;
            }

            if ($request->filled('status')) {
                $tag->status = $request->status;
            }

            $tag->save();

            return response()->json(['message'=> 'Data updated', 'data' => $tag], 200);
            
        } catch (Exception $e) {

            return response()->json(['message'=> $e->getMessage(), 'data' => []], 500);

        }        
    }


    public function create(Request $request) {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:20|unique:tags,name'
        ]);

        if ($validation->fails()) {
            return response()->json(['message'=> $validation->errors()->first(), 'data' => []], 400);
        }

        try {
            
            $tag = Tag::create([
                'name' => $request->name,
                'status' => 'publish'
            ]);

            return response()->json(['message' => 'Success', 'data' => $tag], 200);
            
        } catch (Exception $e) {

            return response()->json(['message'=> $e->getMessage(), 'data' => []], 500);

        }
    }


    public function delete($tag_id) {
        $tag = Tag::where('id', $tag_id)->where('status', '!=', 'deleted')->first();

        if (is_null($tag)) {
            return response()->json(['message' => 'Not Found!', 'data' => []], 404);
        }

        try {
            $tag->status = 'deleted';
            $tag->save();

            return response()->json(['message'=> 'Data deleted', 'data' => []], 200);
            
        } catch (Exception $e) {

            return response()->json(['message'=> $e->getMessage(), 'data' => []], 500);

        }        
    }

}
