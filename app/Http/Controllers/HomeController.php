<?php

namespace App\Http\Controllers;

use App\Models\Statement;
use App\Models\Work;
use App\Services\HomeServices;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HomeController extends Controller
{

    protected $statement;
    protected $homeServices;
    protected $work;

    /**
     * Create a new controller instance.
     *
     * @param Statement $statement
     * @param HomeServices $homeServices
     */
    public function __construct(Statement $statement, Work $work, HomeServices $homeServices)
    {
        $this->statement = $statement;
        $this->work = $work;
        $this->homeServices = $homeServices;
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
        $arr = $this->homeServices->getStatementFlatArray($data);
        return view('pages.home')->with('data', json_encode($arr));
    }

    public function active()
    {

        dd($statements);
        return view('pages.active')->with('data', json_encode($data));
    }
}
