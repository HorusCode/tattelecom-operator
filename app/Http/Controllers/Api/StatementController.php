<?php

namespace App\Http\Controllers\Api;

use App\Models\Statement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class StatementController extends Controller
{
    protected $statement;

    public function __construct(Statement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $status = $this->statement->create([
            'client_id' => $request->client_id,
            'user_id' => Auth::id(),
            'problem' => $request->problem
        ]);

        return response()->json([
            'status' => $status,
            'message' => 'Заявление оформлено!'
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
