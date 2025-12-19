<?php

namespace App\Services;

use App\Models\Playlist;
use App\Models\PlaylistEntry;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function PHPSTORM_META\map;

class PlaylistService
{

    /**
     * @function json format for a playlist
     * @param Playlist $playlist
     * @param bool $addSongs
     * @param bool $addCover
     * @return array
     */
    private function formatPlaylist (Playlist $playlist, bool $addSongs = false, bool $addCover = false):array
    {
        $c = new CoverService();
        $json = [
            'id' => $playlist->id,
            'name' => $playlist->name,
            'sort' => $playlist->sort,
            'entries' => $playlist->entries,
            'duration' => $playlist->duration,
            'size' => $playlist->size,
            'createdAt' => $playlist->created_at
        ];
        $entries = PlaylistEntry::where('playlist_id', $playlist->id)
            ->orderByDesc('sort')
            ->get();
        // should we add all playlist entries for the detail view?
        if ($addSongs) {
            $json['songs'] = array_values(
                $entries
                ->sortByDesc('sort')
                ->map( function ($entry) {
                    return $this->formatPlaylistEntry($entry);
                })->toArray()
            );
        }
        // should we create a cover collage of random songs?
        if ($addCover && count($entries) >= 4) {
            $randomSongs = $entries->take(4)->map(function ($entry) {
                return $this->matchEntryToSong($entry);
            });
            $json['cover'] = $c->getCoverCollage($playlist->id, $randomSongs);
        }

        return $json;
    }

    /**
     * @function get the song that corresponds to a playlist entry.
     * @param PlaylistEntry $entry
     * @return Song
     */
    private function matchEntryToSong (PlaylistEntry $entry): Song
    {
        return Song::where('path', $entry->path)->first();
    }

    /**
     * @function json format for a playlist song
     * @param PlaylistEntry $playlistEntry
     * @return array
     */
    private function formatPlaylistEntry (PlaylistEntry $playlistEntry):array
    {
        $u = new UrlSafeService();
        return [
            'id' => $playlistEntry->id,
            'encodedPath' => $u->encode($playlistEntry->path),
            'song' => $playlistEntry->song,
            'artist' => $playlistEntry->artist,
            'album' => $playlistEntry->album,
            'sort' => $playlistEntry->sort,
            'duration' => $playlistEntry->duration,
            'size' => $playlistEntry->size,
            'createdAt' => $playlistEntry->created_at,
            'updatedAt' => $playlistEntry->updated_at,
            'nowPlaying' => false
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

    /**
     * @function get playlist by id
     * @param string $playlistId
     * @return array
     */
    public function getPlaylistById(string $playlistId): array
    {
        $playlist = Playlist::where('id', $playlistId)
            ->with('songs')
            ->first();
        if (!$playlist) {
            return [];
        }
        $playlist->entries = $playlist->songs()->count();
        $playlist->duration = $playlist->songs()->sum('duration');
        $playlist->size = $playlist->songs()->sum('size');
        return $this->formatPlaylist($playlist, true, true);
    }

    /**
     * @function sort songs of a playlist
     * @param array $changes
     * @param string $playlistId
     * @return array
     */
    public function sortPlaylistEntries(string $playlistId, array $changes): array
    {
        $fd = new FormatService();
        $playlist = Playlist::find($playlistId);
        $start = now();
        Log::channel('api')->info("Sorting PlaylistEntries of playlist '$playlist->name':");
        foreach($changes as $change) {
            $entry = PlaylistEntry::where('id', $change['id'])->first();
            $entry->sort = $change['sort'];
            $entry->save();
            Log::channel('api')->debug("Changing PlaylistEntry '".$change['id']."' to sort=".$change['sort']);
        }
        $ms = $start->diffInMilliseconds(now());
        Log::channel('api')->info("PlaylistEntry sort finished in ".$fd->formatMs($ms).".");
        return $this->getPlaylistById($playlistId);
    }

    /**
     * @function auto sort playlist entries by path
     * @param string $playlistId
     * @return array
     */
    public function autosortPlaylistEntries(string $playlistId): array
    {
        $start = now();
        $fd = new FormatService();
        $playlist = Playlist::find($playlistId);
        Log::channel('api')->info("Auto-Sorting PlaylistEntries of playlist '$playlist->name':");
        $entries = PlaylistEntry::where('playlist_id', $playlistId)
            ->orderBy('path')
            ->get();
        $counter = $entries->count();
        foreach($entries as $entry) {
            if ($entry->sort !== $counter) {
                $entry->sort = $counter;
            }
            $entry->save();
            $counter--;
            Log::channel('api')->debug("Changed PlaylistEntry '".$entry->song."' to sort=".$entry->sort);
        }
        $ms = $start->diffInMilliseconds(now());
        Log::channel('api')->info("PlaylistEntry auto-sort finished in ".$fd->formatMs($ms).".");
        return $this->getPlaylistById($playlistId);
    }

    /**
     * @function delete a playlist entry
     * @param string $playlistId
     * @param string $entryId
     * @return array
     */
    public function deletePlaylistEntry(string $playlistId, string $entryId): array
    {
        $entry = PlaylistEntry::find($entryId);
        $playlist = Playlist::find($playlistId);
        $deleted = PlaylistEntry::where('id', $entryId)
            ->where('playlist_id', $playlistId)
            ->delete();
        if ($deleted) {
            Log::channel('api')->info("PlaylistEntry '$entry->song' deleted from playlist '$playlist->name'.");
        } else {
            Log::channel('api')->info("Could not delete PlaylistEntry '$entry->song' from playlist '$playlist->name'.");
        }
        return $this->getPlaylistById($playlistId);
    }


    public function playSong(string $path): array
    {
        $u = new UrlSafeService();
        $song = Song::where('path', $u->decode($path))
            ->with('artist')
            ->first();
        $storageSongName = "$song->id.mp3";
        $serverPathName = config('collection.server.music.path').$song->path;
        if (Storage::disk('public')->missing($storageSongName)) {
            Log::channel('api')->info("File '$storageSongName' for song '$song->name' missing in public storage.");
            if (file_exists($serverPathName)) {
                Storage::disk('public')->put($storageSongName, file_get_contents($serverPathName));
                Log::channel('api')->error("File '$storageSongName' for song '$song->name' copied to public storage.");
            } else {
                Log::channel('api')->error("File '$serverPathName' does not exist.");
            }
        } else {
            Log::channel('api')->info("File '$storageSongName' already exists in public storage.");
        }
        return [
            'path' => "/storage/$storageSongName",
            'name' => $song->artist->name." - ".$song->name,
        ];
    }

}
