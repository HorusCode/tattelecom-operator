<?php

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
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
                'title' => 'Протяжка кабеля'
            ],
            [
                'title' => 'Очистка дымохода'
            ],
            [
                'title' => 'Пересчёт денег'
            ]
        ];
        for($i = 0; $i < count($data); $i++) {
            Service::create($data[$i]);
        }
    }
}
