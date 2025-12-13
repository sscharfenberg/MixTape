<?php

namespace App\Http\Controllers\Api\Playlists;

use App\Http\Controllers\Controller;
use App\Services\PlaylistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaylistsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function list(Request $request): JsonResponse
    {
        $p = new PlaylistService();
        $playlists = $p->getAllPlaylists();
        return response()->json($playlists);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request): JsonResponse
    {
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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function sort(Request $request): JsonResponse
    {
        $p = new PlaylistService();
        $playlists = $p->sortPlaylists($request->get('changes'));
        return response()->json($playlists);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function edit(Request $request): JsonResponse
    {
        $p = new PlaylistService();
        $playlist = $p->editPlaylist($request);
        return response()->json($playlist);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request): JsonResponse
    {
        $p = new PlaylistService();
        $playlists = $p->deletePlaylist($request);
        return response()->json($playlists);
    }
}
