<?php

namespace App\Services;

use App\Models\Playlist;
use App\Models\PlaylistEntry;
use App\Models\Song;
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
            'duration' => $playlist->duration,
            'size' => $playlist->size
        ];
    }

    /**
     * @function json format for a playlist song
     * @param PlaylistEntry $playlistEntry
     * @return array
     */
    private function formatPlaylistEntry (PlaylistEntry $playlistEntry):array
    {
        return [
            'path' => $playlistEntry->path,
            'song' => $playlistEntry->song,
            'artist' => $playlistEntry->artist,
            'album' => $playlistEntry->album,
            'sort' => $playlistEntry->sort,
            'duration' => $playlistEntry->duration,
            'size' => $playlistEntry->size
        ];
    }

    /**
     * @function create a new playlist
     * @param $request
     * @return array
     */
    public function createNewPlaylist($request): array
    {
        // validate request
        $request->validate([
            'name' => 'required|unique:playlists|max:'.config('collection.db.playlists.name'),
        ]);
        // create playlist
        $sort = Playlist::all()->max('sort') + 1;
        $playlist = Playlist::create([
            'name' => $request->get('name'),
            'sort' => $sort
        ]);
        Log::channel('api')->info("New playlist created. ".json_encode($playlist, JSON_PRETTY_PRINT));
        // and return all playlists
        return $this->getAllPlaylists();
    }

    /**
     * @function get all playlists from database
     * @return array
     */
    public function getAllPlaylists(): array
    {
        $playlists = Playlist::with('songs')
            ->get()
            ->map(function (Playlist $playlist) {
                $playlist->entries = $playlist->songs()->count();
                $playlist->duration = $playlist->songs()->sum('duration');
                $playlist->size = $playlist->songs()->sum('size');
                return $this->formatPlaylist($playlist);
            })->sortByDesc('sort')
            ->toArray();
        return array_values($playlists);
    }

    /**
     * @function get available playlists for a song, excluding playlists
     * that already contain the song.
     * @param Song $song
     * @return array
     */
    public function getAvailablePlaylistsForSong(Song $song): array
    {
        // find playlists that contain this song
        $plEntries = PlaylistEntry::where('path', $song->path)
            ->get()
            ->map(function ($entry) {
                return $entry->playlist_id;
            });
        // filter all playlists, remove playlists that already contain the song.
        return array_values(
            array_filter( $this->getAllPlaylists(), function ($list) use ($plEntries) {
                return !$plEntries->contains($list['id']);
            })
        );
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

    /**
     * @function add a song to a playlist
     * @param string $playlistId
     * @param string $songPath
     * @return array|bool
     */
    public function addSongToPlaylist(string $playlistId, string $songPath): array|bool
    {
        $u = new UrlSafeService();
        $playlist = Playlist::findOrFail($playlistId);
        $song = Song::where('path', $u->decode($songPath))
            ->with('artist')
            ->with('album')
            ->first();
        if (!$song) {
            return [];
        }
        $sort = PlaylistEntry::where('playlist_id', $playlistId)->max('sort') + 1;
        $entry = PlaylistEntry::create([
            'path' => $song->path,
            'song' => $song->name,
            'artist' => $song->artist->name,
            'album' => $song->album->name,
            'sort' => $sort,
            'duration' => $song->duration,
            'size' => $song->size,
            'playlist_id' => $playlist->id,
        ]);
        Log::channel('api')->debug("Added song to playlist '$playlist->name': ".json_encode($entry, JSON_PRETTY_PRINT));
        return [
            'newEntry' => $this->formatPlaylistEntry($entry),
            'playlists' => $this->getAvailablePlaylistsForSong($song)
        ];
    }

}
