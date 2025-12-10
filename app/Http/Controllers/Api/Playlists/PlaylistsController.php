<?php

namespace App\Http\Controllers\Api\Playlists;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
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
    public function show(Request $request): JsonResponse
    {
        $p = new PlaylistService();
        $playlists = $p->getAllPlaylists();
        return response()->json($playlists);
    }
}
