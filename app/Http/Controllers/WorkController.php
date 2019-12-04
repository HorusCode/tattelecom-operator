<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkRequest;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller
{
    protected $work;

    public function __construct(Work $work)
    {
        $this->work = $work;
    }

    public function store(WorkRequest $request)
    {
        $data = [];
        $userId = Auth::id();
        dd($userId, Auth::user());
        foreach ($request->ids as $id) {
            $data[] = [
                'service_user_id' => $id,
                'statement_id' => $request->statement,
                'operator_user_id' => $userId
            ];
        }
        $created = $this->work->insert($data);
        return response()->json($created);
    }
}
