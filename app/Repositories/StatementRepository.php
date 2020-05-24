<?php


namespace App\Repositories;


use App\Models\Statement;

class StatementRepository
{
    protected $statement;

    public function __construct()
    {
        $this->statement = new Statement();
    }


    public function getWithClient(bool $status = false)
    {
        return $this->statement
            ->whereStatus($status)
            ->with('client')
            ->get();
    }



}
