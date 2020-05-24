<?php


namespace App\Services;


use App\Models\Statement;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;

class HomeServices
{
    protected $statement;
    protected $work;

    public function __construct(Statement $statement, Work $work)
    {
        $this->work = $work;
        $this->statement = $statement;
    }


    public function getStatementForClientOperator()
    {
        return $this->statement
            ->whereStatus(true)
            ->with('client')
            ->get();
    }

    public function getStatementForServiceOperator()
    {
        $data = $this->work
            ->whereStatus(0)
            ->whereServiceUserId(Auth::id())
            ->with('operatorUser')
            ->get()
            ->groupBy('statement_id')
        ;
        $arr = [];
        $arrKey = 0;
        foreach ($data as $i => $datum) {
            $stmt = $this->statement->whereStatus(false)->find($i);
            if ($stmt == null) {
                continue;
            }
            $client = $stmt->client->toArray();
            $work = $datum->first();
            $arr[$arrKey] = [
                'id' => $i,
                'problem' => $stmt->problem,
                'status' => $stmt->status,
                'work_id' => $work->id,
                'client' => $client,
                'operator_fullname' => $work->operatorUser->lastname . ' ' . $work->operatorUser->firstname . ' ' . $work->operatorUser->patronymic,
                'created_at' => $work->created_at,
                'updated_at' => $work->updated_at,
            ];
            $arrKey++;
        }
        return $arr;
    }

    public function getActiveWorkForServiceOperator()
    {
        $data = $this->work
            ->whereStatus(1)
            ->whereServiceUserId(Auth::id())
            ->get()
            ->groupBy('statement_id')
        ;
        $arr = [];
        $arrKey = 0;
        foreach ($data as $i => $datum) {
            $stmt = $this->statement->whereStatus(false)->find($i);
            if ($stmt == null) {
                continue;
            }
            $client = $stmt->client;
            $service = [];
            foreach ($datum as $j => $value) {
                $service[] = $value->serviceUser->toArray();
            }
            $work = $datum->first();
            $arr[$arrKey] = [
                'id' => $i,
                'problem' => $stmt->problem,
                'status' => $stmt->status,
                'work_id' =>  $work->id,
                'client' => $client,
                'service' => $service,
                'created_at' => $work->created_at,
                'updated_at' => $work->updated_at,
            ];
            $arrKey++;
        }
        return $arr;
    }


    public function getActiveWorkForClientOperator()
    {
        $data = $this->work
            ->whereStatus(1)
            ->orWhere('status',0)
            ->get()
            ->groupBy('statement_id')
        ;
        $arr = [];
        $arrKey = 0;
        foreach ($data as $i => $datum) {
            $stmt = $this->statement->whereStatus(false)->find($i);
            if ($stmt == null) {
                continue;
            }
            $client = $stmt->client;
            $service = [];
            foreach ($datum as $j => $value) {
                $service[] = $value->serviceUser->toArray();
            }
            $work = $datum->first();
            $arr[$arrKey] = [
                'id' => $i,
                'problem' => $stmt->problem,
                'status' => $stmt->status,
                'work_status' => $work->status,
                'client' => $client,
                'service' => $service,
                'created_at' => $work->created_at,
                'updated_at' => $work->updated_at,
            ];
            $arrKey++;
        }
        return $arr;
    }

    public function getEndedWorkForServiceOperator()
    {
        $data = $this->work
            ->whereStatus(2)
            ->whereServiceUserId(Auth::id())
            ->get()
            ->groupBy('statement_id')
        ;
        $arr = [];
        $arrKey = 0;
        foreach ($data as $i => $datum) {
            $stmt = $this->statement->whereStatus(false)->find($i);
            if($stmt == null) {
                continue;
            }
            $client = $stmt->client;
            $service = [];
            foreach ($datum as $j => $value) {
                $service[] = $value->serviceUser->toArray();
            }
            $work = $datum->first();
            $arr[$arrKey] = [
                'id' => $i,
                'problem' => $stmt->problem,
                'work_id' =>  $work->id,
                'client' => $client,
                'service' => $service,
                'created_at' => $work->created_at,
                'updated_at' => $work->updated_at
            ];
            $arrKey++;
        }
        return $arr;
    }

    public function getEndedWorkForClientOperator()
    {
        $data = $this->work
            ->whereStatus(2)
            ->get()
            ->groupBy('statement_id')
        ;
        $arr = [];
        $arrKey = 0;
        foreach ($data as $i => $datum) {
            $stmt = $this->statement->whereStatus(false)->find($i);
            if($stmt == null) {
                continue;
            }
            $client = $stmt->client;
            $service = [];
            foreach ($datum as $j => $value) {
                $service[] = $value->serviceUser->toArray();
            }
            $work = $datum->first();
            $arr[$arrKey] = [
                'id' => $i,
                'problem' => $stmt->problem,
                'client' => $client,
                'service' => $service,
                'created_at' => $work->created_at,
                'updated_at' => $work->updated_at
            ];
            $arrKey++;
        }
        return $arr;
    }


    public function getStatementFlatArray($data)
    {
        $arr = [];
        foreach ($data as $k => $datum) {
            foreach ($datum as $key => $value) {
                if ($key == 'client') {
                    foreach ($value as $i => $d) {
                        $arr[$k]["client_$i"] = $d;
                    }
                } else {
                    $arr[$k][$key] = $value;
                }
            }
        }
        return $arr;
    }


}
