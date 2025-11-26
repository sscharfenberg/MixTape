<?php

namespace App\Services;

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
     * @return array
     */
    public function formatArtist(Artist $artist): array
    {
        $u = new UrlSafeService;
        return [
            'id' => $artist->id,
            'name' => $artist->name,
            'encodedName' => $u->encode($artist->name)
        ];
    }
}
