<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StatementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $data = [];
        for ($i = 1; $i <= 40; $i++) {
            $data[] = [
                'client_id' => rand(1,30),
                'user_id' => 3,
                'problem' => $faker->realText(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        DB::table('statements')->insert($data);
    }
}
