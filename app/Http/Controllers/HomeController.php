<?php

namespace App\Http\Controllers;

use App\Models\Statement;
use App\Models\Work;
use App\Services\HomeServices;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

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

    public function active() //TODO: Create Resource class
    {
        $data = $this->work
            ->whereStatus('false')
            ->get()
            ->groupBy('statement_id')
            ;
        $arr = [];
        $arrKey = 0;
        foreach ($data as $i => $datum) {
            $stmt = $this->statement->find($i);
            $client = $stmt->client;
            $service = [];
            $date = '';
            foreach ($datum as $j => $value) {
                $service[] = $value->serviceUser->toArray();
                $date = $value->created_at;
            }
            $arr[$arrKey] = [
                'id' => $i,
                'problem' => $stmt->problem,
                'client' => [
                    'id' => $client['id'],
                    'firstname' => $client['firstname'],
                    'lastname' => $client['lastname'],
                    'patronymic' => $client['patronymic'],
                    'address' => $client['address'],
                    'private_face' => $client['private_face'],
                    'net_login' => $client['net_login'],
                ],
                'service' => $service,
                'created_at' => $date
            ];
            $arrKey++;
        }
        return view('pages.active')->with('data', json_encode($arr));
    }
}
