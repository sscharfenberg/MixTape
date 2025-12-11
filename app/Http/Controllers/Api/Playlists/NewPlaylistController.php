<?php

namespace App\Http\Controllers\Api\Playlists;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Services\AudiobookService;
use App\Services\PlaylistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewPlaylistController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request): JsonResponse
    {
        // validate request
        $request->validate([
            'name' => 'required|unique:playlists|max:'.config('collection.db.playlists.name'),
        ]);
        // create new
        $p = new PlaylistService();
        $playlists = $p->createNewPlaylist($request);
        // response
        if (count($playlists) > 0) {
            return response()
                ->json([
                    'status' => 'success',
                    'message' => "Playlist '".$request->name."' erstellt.",
                    'playlists' => $playlists
                ], 200);
        } else {
            return response()
                ->json(['message' => 'Fehler beim erstellen. Check logs.'], 422);
        }
    }

}
