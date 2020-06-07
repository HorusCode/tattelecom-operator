<?php

namespace App\Http\Controllers\Api;

use App\Services\StatisticService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{
    public function statistic(Request $request)
    {
        $statistic =  new StatisticService($request->data, $request->type);
        return response()->json($statistic->getStatistic());
    }
}
