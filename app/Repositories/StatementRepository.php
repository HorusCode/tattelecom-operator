<?php


namespace App\Repositories;


use App\Models\Client;
use App\Models\Statement;
use App\Models\User;
use App\Repositories\Interfaces\StatementRepositoryInterface;

class StatementRepository implements StatementRepositoryInterface
{

    public function all()
    {
        return Statement::all();
    }

    public function getByUser(User $user)
    {
        return Statement::where('user_id', $user->id)->get();
    }

    public function getByClient(Client $client)
    {
        return Statement::where('client_id', $client->id)->get();
    }
}
