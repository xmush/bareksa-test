<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = ['Asuransi', 'Investasi', 'Reksadana', 'Profit', 'Kripto', 'Kebijakan', 'Tips Keuangan'];
        $tags = ['Safe', 'Normal', 'Bad'];

        foreach ($topics as $topic) {
            foreach ($tags as $tag) {
                
                DB::table('tags')->insert([
                    'name' => $tag.' '.$topic,
                    'status' => 'publish',
                    'created_at' => date('Y-m-d')
                ]);
            }
        }
    }
}
