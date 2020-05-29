<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProblemRequest;
use App\Models\Problem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProblemController extends Controller
{
    /* TODO: Create Service, add Repository */
    protected $problem;

    public function __construct(Problem $problem)
    {
        $this->problem = $problem;
    }

    public function searchProblem(Request $request)
    {
        $data = $this->problem
            ->where("name", "LIKE", '%' . $request->text . '%')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

    }


    public function store(ProblemRequest $request)
    {
        $data = $this->problem->create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => !empty($data),
            'message' => $data ? 'Неисправность добавлена!' : 'Произошла ошибка',
            'data' => $data
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
     * @param ProblemRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProblemRequest $request, $id)
    {
        $this->problem->where('id', $id)->update([
            'name' => $request->name
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Неисправность переименована!',
            'data' => $this->problem->find($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $status = $this->problem->destroy($id);
        return response()->json([
            'status' => $status,
            'message' => 'Удаление произведено успешно!'
        ]);
    }


}
