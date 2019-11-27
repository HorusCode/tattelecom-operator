<?php

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'call_operator'
            ],
            [
                'name' => 'client_operator'
            ],
            [
                'name' => 'service'
            ]
        ];
        for($i = 0; $i < count($data); $i++) {
            Employee::create($data[$i]);
        }
    }
}
