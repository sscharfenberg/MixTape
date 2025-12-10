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
        $playlist = $p->createNewPlaylist($request);
        // response
        if ($playlist) {
            return response()
                ->json([
                    'status' => 'success',
                    'message' => "Playlist '".$playlist['name']."' erstellt.",
                    'newPlaylist' => $playlist
                ], 200);
        } else {
            return response()
                ->json(['message' => 'Fehler beim erstellen. Check logs.'], 422);
        }
    }

}
