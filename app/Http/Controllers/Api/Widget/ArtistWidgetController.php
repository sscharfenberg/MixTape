<?php

namespace App\Http\Controllers\Api\Widget;

use App\Http\Controllers\Controller;
use App\Services\AlbumService;
use App\Services\ArtistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistWidgetController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $a = new ArtistService;
        $artists = $a->getRandomArtists();
        if (count($artists) > 0) {
            return response()->json($artists);
        } else {
            return response()
                ->json(['message' => 'Fehler beim laden der kachel Künstler.'], 422);
        }
    }

}
