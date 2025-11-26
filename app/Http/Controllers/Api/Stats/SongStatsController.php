<?php

namespace App\Http\Controllers\Api\Stats;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\SongService;

class SongStatsController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $s = new SongService;
        $stats = $s->getSongStats();
        if (count($stats) > 0) {
            return response()->json($stats, 200);
        } else {
            return response()
                ->json(['message' => 'Fehler beim laden der Song Stats.'], 422);
        }
    }

}
