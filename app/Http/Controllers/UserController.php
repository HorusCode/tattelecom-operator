<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = $this->user
            ->with('employee')
            ->whereRaw("CONCAT_WS(' ', `lastname`,`firstname`,`patronymic`) LIKE ?", '%'.$request->text.'%')
            ->get()
            ->where('employee.name','=', 'service');
        $user = $user->map(function ($item) {
            return [
                'id' => $item->id,
                'firstname' => $item->firstname,
                'lastname' => $item->lastname,
                'patronymic' => $item->patronymic,
            ];
        });
        return response()->json($user);
    }


    public function searchClient(Request $request)
    {
        $data = $this->client
            ->whereRaw("CONCAT_WS(' ', `lastname`,`firstname`,`patronymic`) LIKE ?", '%'.$request->text.'%')
            ->get();
        $data = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'firstname' => $item->firstname,
                'lastname' => $item->lastname,
                'patronymic' => $item->patronymic,
            ];
        });
        return response()->json($data);
    }


    public function getToken()
    {
        return response()->json([token => Auth::user()->api_token]);
    }
}
