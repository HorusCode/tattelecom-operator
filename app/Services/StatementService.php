<?php


namespace App\Services;


use App\Models\Statement;
use App\Repositories\StatementRepository;
use Auth;

class StatementService
{
    private $repo;
    private $model;

    public function __construct()
    {
        $this->repo = new StatementRepository();
        $this->model = new Statement();
    }

    public function get()
    {
        return $this->repo->getWithClient(true);
    }

    public function create(array $arr)
    {
        $arr['user_id'] = Auth::id();
        return $this->model->create($arr);
    }
}
