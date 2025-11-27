<?php

namespace App\Http\Controllers\Api\Music;

use App\Http\Controllers\Controller;
use App\Services\AlbumService;
use App\Services\ArtistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistsController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $a = new ArtistService;
        $artists = $a->getAllArtists();
        if (count($artists) > 0) {
            return response()->json($artists, 200);
        } else {
            return response()
                ->json(['message' => 'Fehler beim laden der Künstler. app:update durchgeführt?'], 422);
        }
    }

}
