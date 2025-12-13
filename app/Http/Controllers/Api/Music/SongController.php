<?php

namespace App\Http\Controllers\Api\Music;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Services\UrlSafeService;
use App\Services\SongService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongController extends Controller
{

    /**
     * @param Request $request
     * @param string $path
     * @return JsonResponse
     * @throws \Exception
     */
    public function show(Request $request, string $path): JsonResponse
    {
        $s = new SongService;
        $song = $s->getSongByPath($path);
        if (count($song) > 0) {
            return response()->json($song);
        } else {
            return response()
                ->json(['message' => 'Es existiert kein Song mit diesem Pfad.'], 422);
        }
    }

}
