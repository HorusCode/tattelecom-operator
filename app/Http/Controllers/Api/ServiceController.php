<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function searchService(Request $request)
    {
        $data = $this->service
            ->where("title", "LIKE", '%' . $request->text . '%')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
