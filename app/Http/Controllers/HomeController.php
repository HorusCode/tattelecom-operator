<?php

namespace App\Http\Controllers;

use App\Models\Statement;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

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
        $data = $this->statement->status(true)->with('client')->get()->toJson();
        return view('pages.home')->with('data', $data);
    }
}
