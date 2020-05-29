<?php


namespace App\Services;


use App\Http\Resources\WorkResource;
use App\Models\Statement;
use App\Models\Work;
use Auth;
use DB;

class HomeServices
{
    protected $statement;
    protected $work;

    public function __construct(Statement $statement, Work $work)
    {
        $this->work = $work;
        $this->statement = $statement;
    }


    public function getStatement(string $for)
    {
        $for = str_replace("_", '', ucwords($for, "_"));
        return $this->{'getStatementFor'.$for}();
    }


    public function getStatementForClientOperator()
    {
        return $this->statement
            ->whereStatus(true)
            ->with('client.services')
            ->get();
    }

    public function getStatementForService()
    {
        /*$data = $this->work
            ->whereStatus(0)
            ->whereServiceUserId(Auth::id())
            ->with('operatorUser')
            ->get()
            ->groupBy('statement_id');
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
        }*/

       /* $arr = DB::table('works')
            ->where('works.status', false)
            ->where('service_user_id', auth()->id())
            ->join('statements', function ($join) {
                $join->on('works.statement_id','=', 'statements.id')
                    ->where('statements.status', false);
            })
            ->rightJoin('clients', 'statements.client_id', '=', 'clients.id')
            ->rightJoin('users', 'statements.user_id', '=', 'users.id')
            ->select(['works.id', 'works.created_at', 'works.status', 'works.statement_id', 'statements'])
            ->get();*/
        $arr = WorkResource::collection($this->work->whereStatus(0)->whereServiceUserId(Auth::id())->get());
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
