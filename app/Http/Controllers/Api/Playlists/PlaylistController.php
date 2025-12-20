<?php

namespace App\Http\Controllers\Api\Playlists;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Services\PlaylistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PlaylistController extends Controller
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

    /**
     * @param Request $request
     * @param string $path
     * @return JsonResponse
     */
    public function show(Request $request, string $path): JsonResponse
    {
        $p = new PlaylistService();
        $res = $p->getPlaylistById($path);
        if (count($res) > 0) {
            return response()->json($res);
        } else {
            return response()->json(['message' => 'Playlist nicht gefunden.'], 422);
        }
    }

    /**
     * @param Request $request
     * @param string $playlistId
     * @return JsonResponse
     * @throws \Exception
     */
    public function sortEntries(Request $request, string $playlistId): JsonResponse
    {
        $p = new PlaylistService();
        $playlist = $p->sortPlaylistEntries($playlistId, $request->get('changes'));
        return response()->json($playlist);
    }

    /**
     * @param Request $request
     * @param string $playlistId
     * @return JsonResponse
     * @throws \Exception
     */
    public function autosortEntries(Request $request, string $playlistId): JsonResponse
    {
        $p = new PlaylistService();
        $playlist = $p->autosortPlaylistEntries($playlistId);
        return response()->json($playlist);
    }

    /**
     * @param Request $request
     * @param string $playlistId
     * @param string $entryId
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteEntry(Request $request, string $playlistId, string $entryId): JsonResponse
    {
        $p = new PlaylistService();
        $playlist = $p->deletePlaylistEntry($playlistId, $entryId);
        return response()->json($playlist);
    }

    /**
     * @param Request $request
     * @param string $path
     * @return JsonResponse
     */
    public function play(Request $request, string $path): JsonResponse
    {
        $p = new PlaylistService();
        return response()->json($p->playSong($path));
    }

    /**
     * @param Request $request
     * @param string $playlistId
     * @return StreamedResponse
     */
    public function exportM3u(Request $request, string $playlistId): StreamedResponse
    {
        $p = new PlaylistService();
        $download = $p->exportM3u(
            $playlistId,
            $request->get('encoding'),
            $request->get('type'),
            $request->get('prefixPath')
        );
        return Storage::disk('downloads')->download(
            $download['storageName'],
            $download['downloadName'],
            [ 'Content-Type' => 'application/vnd' ]
        );
    }
}
