<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // initiate faker
        $faker = Faker::create('id_ID');

        for($i = 1; $i <= 100; $i++){

            $topic = DB::table('topics')->find(rand(1, 7));
            $total_tag = rand(1,3);
            $status_array = ['draft', 'deleted', 'publish'];
            
            $id_article = DB::table('articles')->insertGetId([
    			'topic_id' => $topic->id,
    			'title' => $topic->name.' '.$faker->sentence(4, true),
    			'description' => $faker->text(1000),
    			'status' => Arr::random($status_array),
    			'created_at' => date('Y-m-d')
    		]);

            for($j=1; $j<=$total_tag; $j++) {
                $tag = DB::table('tags')->find(rand(1,21));

                DB::table('article_tags')->insert([
                    'article_id' => $id_article,
                    'tag_id' => $tag->id,
                    'status' => 'publish',
                    'created_at' => date('Y-m-d')
                ]);

            }

        }
    }
}
