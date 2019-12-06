<?php

namespace App\Http\Controllers;

use App\Models\Statement;
use App\Models\Work;
use App\Services\HomeServices;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    protected $homeServices;

    /**
     * Create a new controller instance.
     *
     * @param HomeServices $homeServices
     */
    public function __construct(HomeServices $homeServices)
    {
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
        $title = '';
        switch (Auth::user()->employee->name) {
            case 'client_operator':
                $data = $this->homeServices->getStatementForClientOperator();
                $title = 'Новые заявления';
                break;
            case 'service':
                $data = $this->homeServices->getStatementForServiceOperator();
                $title = 'Новые работы';
                break;
        }
        $arr = $this->homeServices->getStatementFlatArray($data);
        return view('pages.home')->with(['data' => json_encode($arr), 'title' => $title]);
    }

    public function active() //TODO: Create Resource class
    {
        $title = '';
        $arr = [];
        switch (Auth::user()->employee->name) {
            case 'client_operator':
                $arr = $this->homeServices->getActiveWorkForClientOperator();
                $title = 'Новые заявления';
                break;
            case 'service':
                $arr = $this->homeServices->getActiveWorkForServiceOperator();
                $title = 'Активные работы';
                break;
        }

        return view('pages.active')->with(['data' => json_encode($arr), 'title'=> $title]);
    }


    public function ended() //TODO: Create Resource class
    {
        $title = '';
        $arr = [];
        switch (Auth::user()->employee->name) {
            case 'client_operator':
                $arr = $this->homeServices->getEndedWorkForClientOperator();
                $title = 'Закрытые заявления';
                break;
            case 'service':
                $arr = $this->homeServices->getEndedWorkForServiceOperator();
                $title = 'Завершённые работы';
                break;
        }

        return view('pages.ended')->with(['data' => json_encode($arr), 'title'=> $title]);
    }
}
