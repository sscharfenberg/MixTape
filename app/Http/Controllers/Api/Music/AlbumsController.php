<?php

namespace App\Http\Controllers\Api\Music;

use App\Http\Controllers\Controller;
use App\Services\AlbumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumsController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $a = new AlbumService;
        $allAlbums = $a->getAllAlbums();
        if (count($allAlbums) > 0) {
            return response()->json($allAlbums, 200);
        } else {
            return response()
                ->json(['message' => 'Fehler beim laden der Alben. app:update durchgeführt?'], 422);
        }
    }

}
