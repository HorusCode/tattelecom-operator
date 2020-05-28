<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IDsRequest;
use App\Models\Problem;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceProblemController extends Controller
{
    protected $service;
    protected $problem;

    public function __construct(Service $service, Problem $problem)
    {
        $this->service = $service;
        $this->problem= $problem;
    }

    public function index()
    {
        return response()->json(['data' => $this->service->with('problems')->get()->toArray()]);
    }

    public function store(IDsRequest $request)
    {
        $service = $this->service->find($request->current_id);
        $service->problems()->syncWithoutDetaching($request->ids);

        return response()->json([
            'status' => true,
            'message' => 'Неисправности у услуги обновлены!'
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $service = $this->service->find($id);
        $service->problems()->detach([$request->problem_id]);
        return  response()->json([
            'status' => true,
            'message' => 'Удаление произведено успешно!'
        ]);
    }
}
