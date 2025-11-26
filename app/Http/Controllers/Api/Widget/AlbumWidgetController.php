<?php

namespace App\Http\Controllers\Api\Widget;

use App\Http\Controllers\Controller;
use App\Services\AlbumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumWidgetController extends Controller
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
                ->json(['message' => 'Fehler beim laden der Kachel Alben.'], 422);
        }
    }

}
