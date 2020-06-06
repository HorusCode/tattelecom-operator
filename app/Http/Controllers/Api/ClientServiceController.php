<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IDsRequest;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;

class ClientServiceController extends Controller
{

    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function store(IDsRequest $request)
    {
        $client = $this->client->find($request->current_id);
        $client->services()->syncWithoutDetaching($request->ids);

        return response()->json([
            'status' => true,
            'message' => 'Услуги абонента обновлены!'
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $client = $this->client->find($id);
        $client->services()->detach([$request->service_id]);
        return  response()->json([
            'status' => true,
            'message' => 'Удаление произведено успешно!'
        ]);
    }
}
