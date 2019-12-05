<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkRequest;
use App\Models\Statement;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller
{
    protected $work;
    protected $statement;

    public function __construct(Work $work, Statement $statement)
    {
        $this->work = $work;
        $this->statement = $statement;
    }

    public function store(WorkRequest $request)
    {
        $data = [];
        foreach ($request->ids as $id) {
            $data[] = [
                'service_user_id' => $id,
                'statement_id' => $request->statement,
                'operator_user_id' => Auth::id(),
                'created_at' => date('Y-m-d H-i-s'),
                'updated_at' => date('Y-m-d H-i-s')
            ];
        }
        $k = $this->statement->find($request->statement)->update(['status' => false]);
        dd($k);
        $created = $this->work->insert($data);
        return response()->json($created);
    }
}
