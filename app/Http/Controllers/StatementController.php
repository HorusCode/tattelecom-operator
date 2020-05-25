<?php


namespace App\Http\Controllers;


use App\Services\HomeServices;
use Auth;
use Illuminate\Contracts\Support\Renderable;

class StatementController extends Controller
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
    public function inactive()
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
        // $arr = $this->homeServices->getStatementFlatArray($data);
        return view('pages.statement')->with([
            'data' => json_encode($data),
            'title' => $title,
            'showBtn' => 'true',
            'btnType' => 'start'
        ]);
    }

    public function active() //TODO: Create Resource class
    {
        $title = '';
        $arr = [];
        switch (Auth::user()->employee->name) {
            case 'client_operator':
                $arr = $this->homeServices->getActiveWorkForClientOperator();
                $title = 'Активные заявления';
                $showBtn = 'false';
                break;
            case 'service':
                $arr = $this->homeServices->getActiveWorkForServiceOperator();
                $title = 'Активные работы';
                $showBtn = 'true';
                break;
        }

        return view('pages.statement')->with([
            'data' => json_encode($arr),
            'title'=> $title,
            'showBtn' => $showBtn,
            'btnType' => 'stop'
        ]);
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

        return view('pages.statement')->with(['data' => json_encode($arr), 'title'=> $title]);
    }

    public function create()
    {
        return view('pages.form-statement')->with(['title' => 'Оформить заявление']);
    }

    public function problem()
    {
        return view('pages.problem')->with(['title' => 'Оформить заявление']);
    }
}
