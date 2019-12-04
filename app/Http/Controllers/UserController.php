<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function searchServiceUser(Request $request)
    {
        $user = $this->user->whereRaw("CONCAT(`lastname`, ' ', `firstname`, ' ', `patronymic`) LIKE ?", '%'.$request->text.'%')->get()->toArray();
        return response()->json($user);
    }

    public function store(Request $request)
    {

    }


}
