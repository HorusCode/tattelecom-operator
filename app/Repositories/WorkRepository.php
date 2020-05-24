<?php


namespace App\Repositories;


use App\Models\Work;

class WorkRepository
{
    protected $work;

    public function __construct(Work $work)
    {
        $this->work = $work;
    }

    public function getServiceOperatorWork(int $operator_id,bool $status)
    {
        return $this->work
            ->whereStatus($status)
            ->whereServiceUserId($operator_id)
            ->with('operatorUser')
            ->get()
            ->groupBy('statement_id');
    }

}
