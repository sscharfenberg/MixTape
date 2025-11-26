<?php

namespace App\Http\Controllers\Api\Widget;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\SongService;

class SongWidgetController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $s = new SongService;
        $stats = $s->getRandomSongs();
        if (count($stats) > 0) {
            return response()->json($stats, 200);
        } else {
            return response()
                ->json(['message' => 'Fehler beim laden der Kachel Songs.'], 422);
        }
    }

}
