<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
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
        for ($i = 1; $i <= 30; $i++) {
            $data[] = [
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'patronymic' => $faker->word,
                'phone' => "890612208{$i}",
                'passport_number' => $faker->randomNumber('6'),
                'passport_series' => $faker->randomNumber('4'),
                'net_login' => Str::random('5'),
                'address' => $faker->address
            ];
        }
        DB::table('clients')->insert($data);
    }
}
