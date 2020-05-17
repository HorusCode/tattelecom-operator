<?php


namespace App\Repositories\Interfaces;


use App\Models\User;

interface StatementRepositoryInterface
{
    public function all();

    public function getByUser(User $user);
}
