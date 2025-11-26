<?php

namespace App\Http\Controllers\Api\Music;

use App\Http\Controllers\Controller;
use App\Services\SongService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongsController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $s = new SongService;
        $allSongs = $s->getAllSongs();
        if (count($allSongs) > 0) {
            return response()->json($allSongs, 200);
        } else {
            return response()
                ->json(['message' => 'Fehler beim laden der Songs. app:update durchgeführt?'], 422);
        }
    }

}
