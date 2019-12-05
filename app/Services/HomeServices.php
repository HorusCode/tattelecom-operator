<?php


namespace App\Services;


use Illuminate\Support\Facades\DB;

class HomeServices
{
    public function getStatementFlatArray($data)
    {
        $arr = [];
        foreach ($data as $k => $datum)
        {
            foreach ($datum as $key => $value)
            {
                if($key == 'client') {
                    foreach ($value as $i => $d) {
                        $arr[$k]["client_$i"] = $d;
                    }
                } else {
                    $arr[$k][$key] = $value;
                }
            }
        }
        return $arr;
    }

    public function getActiveStatements()
    {
        $data = DB::table('statements')
            ->get()
            ->groupBy('stmt_id')
            ->toArray();
        return $data;
    }

}