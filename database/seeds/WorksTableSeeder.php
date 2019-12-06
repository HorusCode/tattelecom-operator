<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WorksTableSeeder extends Seeder
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
        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'operator_user_id' => 1,
                'service_user_id' => 2,
                'statement_id' => $i,
                'status' => rand(0, 2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        DB::table('works')->insert($data);
    }
}
