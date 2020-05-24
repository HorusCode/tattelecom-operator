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
                'email' => $faker->email,
                'address' => $faker->address,
                'private_face' => rand(0,1)
            ];
        }
        DB::table('clients')->insert($data);
    }
}
