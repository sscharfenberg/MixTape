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
     */
    public function show(Request $request, string $path): JsonResponse
    {
        $u = new UrlSafeService;
        $s = new SongService;
        $song = Song::where('path', $u->decode($path))
            ->with('artist')
            ->with('album')
            ->with('genre')
            ->first();
        if ($song) {
            $json = $s->formatSong($song, true, true);
            $json['nav'] = $s->getNavigation($song);
            return response()->json($json);
        } else {
            return response()
                ->json(['message' => 'Es existiert kein Song mit diesem Pfad.'], 422);
        }
    }

}
