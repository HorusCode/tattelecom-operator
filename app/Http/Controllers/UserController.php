<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Notifications\NewWorkCreated;
use Auth;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;
    protected $client;

    public function __construct(User $user, Client $client)
    {
        $this->user = $user;
        $this->client = $client;
    }


    public function searchServiceUser(Request $request)
    {
        $user = DB::table('users')
            ->select(['users.id','users.firstname','users.lastname','users.patronymic',DB::raw('COUNT(works.id) as work_count')])
            ->whereRaw("CONCAT_WS(' ', `lastname`,`firstname`,`patronymic`) LIKE ?", '%'.$request->text.'%')
            ->join('employees', function ($join) {
                $join->on('users.employee_id', '=', 'employees.id')->where('employees.name', '=', 'service');
            })
            ->leftJoin('works', function ($join) {
                $join->on('users.id', '=', 'works.service_user_id')->where('works.status', '<', '2');
            })
            ->groupBy('users.id')
            ->orderBy('work_count', 'asc')
            ->get();

        /*$user = $this->user
            ->with('employee')

            ->get()
            ->where('employee.name','=', 'service');
        $user = $user->map(function ($item) {
            return [
                'id' => $item->id,
                'firstname' => $item->firstname,
                'lastname' => $item->lastname,
                'patronymic' => $item->patronymic,
            ];
        });*/
        return response()->json($user);
    }


    public function searchClient(Request $request)
    {
        $data = $this->client
            ->with('services.problems')
            ->whereRaw("CONCAT_WS(' ', `lastname`,`firstname`,`patronymic`) LIKE ?", '%'.$request->text.'%')
            ->get();
        $data = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'firstname' => $item->firstname,
                'lastname' => $item->lastname,
                'patronymic' => $item->patronymic,
                'services' => $item->services,
            ];
        });
        return response()->json($data);
    }


    public function getToken()
    {
        return response()->json([token => Auth::user()->api_token]);
    }
}
