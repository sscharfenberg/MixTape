<?php

namespace App\Services;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlaylistService
{

    /**
     * @function json format for a playlist
     * @param Playlist $playlist
     * @return array
     */
    private function formatPlaylist (Playlist $playlist):array
    {
        return [
            'id' => $playlist->id,
            'name' => $playlist->name,
            'sort' => $playlist->sort,
            'entries' => $playlist->entries,
            'duration' => $playlist->duration
        ];
    }

    /**
     * @function create a new playlist
     * @param $request
     * @return array
     */
    public function createNewPlaylist($request): array
    {
        // create playlist
        $playlist = Playlist::create([
            'name' => $request->get('name'),
            'sort' => 0
        ]);
        Log::channel('api')->info("New playlist with sort=0 created. ".json_encode($playlist, JSON_PRETTY_PRINT));

        // increment sort by 1 so the new playlist has sort = 1
        $affectedRows = Playlist::increment('sort');
        Log::channel('api')->info("Incremented sort of $affectedRows playlists by 1.");

        // fetch new row from DB
        $newRow = Playlist::orderBy('sort')->first();
        $newRow->entries = 0;
        $newRow->duration = 0;
        // and return all playlists
        return $this->getAllPlaylists();
    }

    /**
     * @function get all playlists from database
     * @return array
     */
    public function getAllPlaylists(): array
    {
        $playlists = Playlist::all()
            ->map(function (Playlist $playlist) {
                $playlist->entries = $playlist->entries()->count();
                $playlist->duration = $playlist->entries()->sum('duration');
                return $this->formatPlaylist($playlist);
            })->sortBy('sort')
            ->toArray();
        return array_values($playlists);
    }

    /**
     * @function change sort order of playlists
     * @param array $changes
     * @return array
     */
    public function sortPlaylists(array $changes): array
    {
        $fd = new FormatService();
        $start = now();
        Log::channel('api')->info("Sorting ".count($changes)." playlists:");
        foreach($changes as $change) {
            $playlist = Playlist::where('id', $change['id'])->first();
            $playlist->sort = $change['sort'];
            $playlist->save();
            Log::channel('api')->debug("Changing Playlist ".$change['id']." to sort=".$change['sort']);
        }
        $ms = $start->diffInMilliseconds(now());
        Log::channel('api')->info("Playlist sort finished in ".$fd->formatMs($ms).".");
        return $this->getAllPlaylists();
    }

    /**
     * @function edit a playlist by changing the name.
     * @param Request $request
     * @return array
     */
    public function editPlaylist(Request $request): array
    {
        $playlist = Playlist::findOrFail($request->get('id'))->first();
        $playlist->name = $request->get('name');
        $playlist->save();
        $updatedPlaylist = Playlist::findOrFail($request->get('id'))->first();
        Log::channel('api')->debug("Edited Playlist to ".json_encode($playlist, JSON_PRETTY_PRINT));
        return $this->formatPlaylist($updatedPlaylist);
    }

    /**
     * @function delete a playlist by primary key
     * @param Request $request
     * @return array
     */
    public function deletePlaylist(Request $request): array
    {
        $playlist = Playlist::findOrFail($request->get('id'));
        $playlist->delete();
        Log::channel('api')->debug("Deleted Playlist: ".json_encode($playlist, JSON_PRETTY_PRINT));
        return $this->getAllPlaylists();
    }

}
