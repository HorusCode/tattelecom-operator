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

    private function pascalCase(string $str)
    {
        return str_replace("_", '', ucwords($str, "_"));
    }


    public function getStatement(string $for)
    {
        $for = $this->pascalCase($for);
        return $this->{'getStatementFor' . $for}();
    }

    public function getActiveWork(string $for)
    {
        $for = $this->pascalCase($for);
        return $this->{'getActiveWorkFor' . $for}();
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

        return WorkResource::collection($this->work->whereStatus(0)->whereServiceUserId(Auth::id())->get());
    }

    public function getActiveWorkForServiceOperator()
    {
        /*$data = $this->work
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
        }*/

        return WorkResource::collection($this->work->whereStatus(0)->whereServiceUserId(Auth::id())->get());
    }


    public function getActiveWorkForClientOperator()
    {
        /*$data = $this->work
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
        }*/

        return WorkResource::collection($this->work->whereStatus(1)->orWhere('status', 0)->get());
    }

    public function getEndedWorkForServiceOperator()
    {
        $data = $this->work
            ->whereStatus(2)
            ->whereServiceUserId(Auth::id())
            ->get()
            ->groupBy('statement_id');
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
                'work_id' => $work->id,
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
        /*$data = $this->work
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
        }*/
        return WorkResource::collection($this->work->whereStatus(2)->get());
    }


}
