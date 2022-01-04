<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    
    public function run()
    {
        $topics = ['Asuransi', 'Investasi', 'Reksadana', 'Profit', 'Kripto', 'Kebijakan', 'Tips Keuangan'];

        foreach ($topics as $topic) {
            DB::table('topics')->insert([
                'name' => $topic,
                'status' => 'publish',
                'created_at' => date('Y-m-d')
            ]);
        }

    }
}
