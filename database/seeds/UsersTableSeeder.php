<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create 3 users for 3 employee: client managment operator, call center operator, service manager
        $data = [
            [
                'firstname' => 'OperatorFirstName',
                'lastname' => 'OperatorLastName',
                'patronymic' => 'OperatorPatronymic',
                'phone' => '89061220846',
                'passport_number' => 123456,
                'passport_series' => 1234,
                'login' => 'operator',
                'employee_id' => 2,
                'password' => Hash::make('1234')
            ],
            [
                'firstname' => 'ServiceFirstName',
                'lastname' => 'ServiceLastName',
                'patronymic' => 'ServicePatronymic',
                'phone' => '89061220846',
                'passport_number' => 123456,
                'passport_series' => 1234,
                'login' => 'service',
                'employee_id' => 3,
                'password' => Hash::make('1234')
            ],
            [
                'firstname' => 'CallFirstName',
                'lastname' => 'CallLastName',
                'patronymic' => 'CallPatronymic',
                'phone' => '89061220846',
                'passport_number' => 123456,
                'passport_series' => 1234,
                'login' => 'call',
                'employee_id' => 1,
                'password' => Hash::make('1234')
            ]
        ];

        for($i = 0; $i < count($data); $i++) {
            User::create($data[$i]);
        }


    }
}
