<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call(EmployeeTableSeeder::class);
       $this->call(UsersTableSeeder::class);
       $this->call(ClientsTableSeeder::class);
       $this->call(StatementsTableSeeder::class);
       $this->call(ProblemsTableSeeder::class);
       $this->call(ServicesTableSeeder::class);
   //  $this->call(WorksTableSeeder::class);
    }
}
