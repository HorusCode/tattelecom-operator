<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StatementResource;
use App\Models\Statement;
use App\Services\StatementService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class StatementController extends Controller
{

    private $role;
    protected $stmt;
    protected $statement;

    public function __construct(StatementService $service, Statement $statement)
    {
        $this->stmt = $service;
        $this->statement = $statement;
       // $this->role = Auth::user()->getRole();
    }


    public function index()
    {
        return StatementResource::collection($this->stmt->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $status = $this->stmt->create($request->all());

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Заявление оформлено!' : 'Не получилось создать заявление.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $status = $this->statement->destroy($id);
        return  response()->json([
            'status' => $status,
            'message' => 'Удаление произведено успешно!'
        ]);
    }
}
