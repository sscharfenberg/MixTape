<?php

namespace App\Http\Controllers\Api\Stats;

use App\Http\Controllers\Controller;
use App\Services\AlbumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumStatsController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $a = new AlbumService;
        $stats = $a->getRandomAlbums();
        if (count($stats) > 0) {
            return response()->json($stats);
        } else {
            return response()
                ->json(['message' => 'Fehler beim laden der Album Stats.'], 422);
        }
    }

}
