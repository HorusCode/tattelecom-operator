<?php

namespace App\Http\Controllers;

use App\Models\Statement;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HomeController extends Controller
{

    protected $statement;

    /**
     * Create a new controller instance.
     *
     * @param Statement $statement
     */
    public function __construct(Statement $statement)
    {
        $this->statement = $statement;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $data = $this->statement
            ->whereStatus(true)
            ->with('client')
            ->get()->toArray();
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
        return view('pages.home')->with('data', json_encode($arr));
    }
}
