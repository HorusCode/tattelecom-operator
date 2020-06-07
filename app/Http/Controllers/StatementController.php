<?php


namespace App\Http\Controllers;


use App\Events\StatementAdded;
use App\Notifications\NewWorkCreated;
use App\Services\HomeServices;
use Auth;
use Illuminate\Contracts\Support\Renderable;

class StatementController extends Controller
{
    protected $homeServices;
    protected $role;
    /**
     * Create a new controller instance.
     *
     * @param HomeServices $homeServices
     */
    public function __construct(HomeServices $homeServices)
    {
        $this->homeServices = $homeServices;
        $this->middleware(function ($request, $next) {
            $this->role = Auth::user()->getRole();
            return $next($request);
        });

    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function inactive()
    {

        $data = $this->homeServices->getStatement($this->role);
        event(new StatementAdded(['kek' => 'lol']));
        return view('pages.statement')->with([
            'data' => json_encode($data),
            'title' => $this->role == 'service' ? 'Новые заявления' : 'Новые работы',
            'showBtn' => 'true',
            'btnType' => 'start'
        ]);
    }

    public function active()
    {
        $arr = $this->homeServices->getActiveWork($this->role);

        return view('pages.statement')->with([
            'data' => json_encode($arr),
            'title'=> $this->role == 'service' ? 'Активные заявления' : 'Активные работы',
            'showBtn' => $this->role == 'service' ? 'true' : 'false',
            'btnType' => 'stop'
        ]);
    }


    public function ended()
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
}
