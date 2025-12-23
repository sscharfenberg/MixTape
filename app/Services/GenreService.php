<?php

namespace App\Services;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;

class GenreService
{

    /**
     * @function determine the top genres by songs and return a formatted array
     * @return array
     */
    public function getGenresByDuration(): array
    {
        $json = [];
        Genre::with('songs')
            ->get()
            ->map(function ($genre) {
                $genre->numSongs = $genre->songs->count();
                $genre->duration = $genre->songs->sum('duration');
                $genre->size = $genre->songs->sum('size');
                $genre->numArtists = $genre->songs->unique('album_artist_id')->count();
                return $genre;
            })->sortByDesc('duration')
            ->each(function ($genre) use (&$json) {
                $json[] = $this->formatGenre($genre);
            });
        return $json;
    }

    /**
     * @function format a single genre
     * @param Genre $genre
     * @return array
     */
    public function formatGenre(Genre $genre): array
    {
        $u = new UrlSafeService;
        return [
            'id' => $genre->id,
            'encodedName' => $u->encode($genre->name),
            'name' => $genre->name,
            'numSongs' => $genre->numSongs,
            'numArtists' => $genre->numArtists,
            'duration' => $genre->duration,
            'size' => $genre->size
        ];
    }

    /**
     * @function get a specific genre by encoded name
     * @param string $name
     * @return array
     * @throws \Exception
     */
    public function getGenreByName(string $name): array
    {
        $u = new UrlSafeService;
        $a = new ArtistService;
        $al = new AlbumService;
        $s = new SongService;
        $genre = Genre::where('name', $u->decode($name))
            ->with('songs')
            ->first();
        $genre->numSongs = $genre->songs->count();
        $genre->duration = $genre->songs->sum('duration');
        $genre->size = $genre->songs->sum('size');
        $genre->numArtists = $genre->songs->unique('album_artist_id')->count();
        $json['genre'] = $this->formatGenre($genre);
        $json['songs'] = $genre->songs->map(function ($song) use ($s) {
            return $s->formatSong($song);
        });
        $artistIds = $genre->songs->unique('album_artist_id')->map(function ($song) {
            return $song->artist_id;
        })->toArray();
        $json['artists'] = Artist::whereIn('id', array_values($artistIds))
            ->with('albums')
            ->with('songs')
            ->get()
            ->map(function ($artist) use ($a) {
                return $a->formatArtist($artist, false, false);
            })->toArray();
        $albumIds = $genre->songs->unique('album_id')->map(function ($song) {
            return $song->album_id;
        })->toArray();
        $json['albums'] = Album::whereIn('id', array_values($albumIds))
            ->with('songs')
            ->get()
            ->map(function ($album) use ($al) {
                $album->numSongs = $album->songs->count();
                $album->duration = $album->songs->sum('duration');
                $album->fileSize = $album->songs->sum('size');
                $album->discs = $album->songs->unique('disc')->count();
                return $al->formatAlbum($album);
            })->toArray();
        return $json;
    }

    /**
     * @function search database for genres
     * @param string $name
     * @return array
     */
    public function searchGenreByName(string $name): array
    {
        $json = [];
        $u = new UrlSafeService;
        $l = new LibraryService;
        $f = new FormatService;
        $segments = explode(" ", $name);
        $genre = Genre::whereNotNull('id');
        foreach ($segments as $segment) {
            $genre = $genre->whereLike('name', "%$segment%", caseSensitive: false);
        }
        $genre->with('songs')
            ->take(config('collection.search_max.genres'))
            ->get()
            ->map(function ($genre) {
                $genre->duration = $genre->songs->sum('duration');
                return $genre;
            })->sortByDesc('duration')
            ->each(function ($genre) use (&$json, $l, $u, $f) {
                $json[] = $l->formatSearchItem(
                    'genre',
                    'genre',
                    $u->encode($genre->name),
                    $genre->name,
                    $f->formatDuration($genre->duration),
                    "time"
                );
            })->toArray();
        return array_values($json);
    }
}
