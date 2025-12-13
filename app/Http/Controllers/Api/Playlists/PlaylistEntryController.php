<?php

namespace App\Http\Controllers\Api\Playlists;

use App\Http\Controllers\Controller;
use App\Services\PlaylistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaylistEntryController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addSong(Request $request): JsonResponse
    {
        $p = new PlaylistService();
        $res = $p->addSongToPlaylist($request->get('playlistId'), $request->get('songPath'));
        if (count($res) > 0) {
            return response()->json($res);
        } else {
            return response()->json(['message' => 'Fehler beim hinzufügen. Logfiles prüfen!'], 422);
        }
    }

}
