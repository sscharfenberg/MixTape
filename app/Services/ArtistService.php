<?php

namespace App\Services;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Song;
use App\Services\UrlSafeService;
use \Illuminate\Support\Collection;

class ArtistService
{

    /**
     * @function json format artist data
     * @param Artist $artist
     * @param bool $addSongs
     * @return array
     */
    public function formatArtist(Artist $artist, bool $addSongs = false): array
    {
        $u = new UrlSafeService;
        $artist = [
            'id' => $artist->id,
            'name' => $artist->name,
            'encodedName' => $u->encode($artist->name),
            'albums' => $artist->albums->map(function (Album $album) use ($u) {
                return [
                    'id' => $album->id,
                    'name' => $album->name,
                    'encodedName' => $u->encode($album->name),
                    'year' => $album->year,
                ];
            })->toArray(),
            'numSongs' => $artist->songs->count(),
            'songsDuration' => $artist->songs->sum('duration'),
            'songsFileSize' => $artist->songs->sum('size'),
        ];
        if ($addSongs) {
            $s = new SongService;
            $artist['songs'] = $artist->songs->map(function (Song $song) use ($s) {
               return $s->formatSong($song);
            })->toArray();
        }
        return $artist;
    }

    /**
     * @function get random artists and return array for json
     * @return array
     */
    public function getRandomArtists():array
    {
        $artists = Artist::with('songs')
            ->with('albums')
            ->inRandomOrder()
            ->get()
            ->filter(function ($artist) {
                return $artist->albums->count() > 0;
            })->take(config('collection.stats.artists.random'))
            ->map(function (Artist $artist) {
                return $this->formatArtist($artist);
            })->toArray();
        return array_values($artists);
    }

    /**
     * @function get all artists in json format, including non albumArtists
     * @return array
     */
    public function getAllArtists():array
    {
        $artists = Artist::with('songs')
            ->with('albums')
            ->get()
            ->map(function (Artist $artist) {
                return $this->formatArtist($artist);
            })->toArray();
        return array_values($artists);
    }

}
