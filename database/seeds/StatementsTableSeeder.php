<?php

use Illuminate\Database\Seeder;

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
                'problem' => $faker->realText(10)
            ];
        }
        DB::table('statements')->insert($data);
    }
}
