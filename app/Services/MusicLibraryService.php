<?php
namespace App\Services;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use wapmorgan\Mp3Info\Mp3Info;

class MusicLibraryService {

    /**
     * @function main function that renews the database
     * @return bool
     * @throws \Exception
     */
    public function renewDatabase(): bool
    {
        Log::channel('lib')->info("starting function MusicLibraryService->renewDatabase()");
        $lib = new LibraryService;
        $list = $lib->getFileList(
            config('collection.server.music.csv'),
            config('collection.server.music.path')
        );
        if (count($list) > 0) {
            Log::channel('lib')->debug("found ".count($list)." files.");
            // empty database
            $this->pruneDB();
            // main loop over the file list
            $list->each( function (string $file) {
                $this->insertSong($file);
            });
            Log::channel('lib')->info("finished music database renewal with no errors.");
            return true;
        } else {
            Log::channel('lib')->error("no files found.");
            return false;
        }
    }



    /**
     * @function truncate database tables
     * @return void
     */
    public function pruneDB(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Genre::truncate();
        Log::channel('lib')->info("table 'genres' truncated.");
        Artist::truncate();
        Log::channel('lib')->info("table 'artists' truncated.");
        Album::truncate();
        Log::channel('lib')->info("table 'albums' truncated.");
        Song::truncate();
        Log::channel('lib')->info("table 'songs' truncated.");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Log::channel('lib')->info("music db tables truncated.");
    }

    /**
     * @function analyze file, then insert song into db.
     * @param string $path
     * @return void
     * @throws \Exception
     */
    public function insertSong(string $path): void
    {
        $full_path = config('collection.server.music.path').$path;
        $lib = new LibraryService;
        Log::channel('lib')->info("analyzing '".$full_path."'");

        // call Mp3Info library
        $audio = new Mp3Info($full_path, true);
        $modifiedAt = filemtime($full_path);

        // prepare artist, album and genre, and determine the corresponding ids
        $artistId = $this->getArtist($audio->tags['artist']);
        $albumArtistId = $this->getArtist(
            array_key_exists('TPE2', $audio->tags2)
            ? $audio->tags2['TPE2']
            : $audio->tags['artist']
        );
        $artistName = $audio->tags['artist'] ?: $audio->tags2['TPE2'];
        $albumId = $this->getAlbum(
            $audio->tags['album'],
            $albumArtistId,
            $artistName,
            (int)$audio->tags1['year']
        );
        $genreId = $this->getGenre($audio->tags['genre']);

        // fields that we should have in the mp3 tags
        $song = [
            'name' => $audio->tags['song'],
            'artist_id' => $artistId,
            'album_artist_id' => $albumArtistId,
            'album_id' => $albumId,
            'genre_id' => $genreId,
            'publisher' => $audio->tags2['TPUB'] ?? null,
            'composer' => $audio->tags2['TCOM'] ?? null,
            'path' => $path,
        ];

        // fields that we might not have in the mp3 tags
        if (isset($audio->bitRate)) {
            $song['bit_rate'] = $audio->bitRate;
        }
        if (array_key_exists('track', $audio->tags)) {
            $song['track'] = $lib->getNum($audio->tags['track']);
        }
        if (isset($audio->tags2) && array_key_exists('TPOS', $audio->tags2)) {
            $song['disc'] = $lib->getNum($audio->tags2['TPOS']);
        }
        if (isset($audio->codecVersion) && isset($audio->layerVersion)) {
            $song['codec'] = "MPEG $audio->codecVersion Layer $audio->layerVersion";
        }
        if (isset($audio->_fileSize) && $audio->_fileSize > 0) {
            $song['size'] = $audio->_fileSize;
        }
        if (isset($audio->duration) && $audio->duration > 0) {
            $song['duration'] = $audio->duration;
        }
        if ($modifiedAt) {
            $song['modified_at'] = Carbon::createFromTimestamp($modifiedAt);
        }
        if (isset($audio->sampleRate) && $audio->sampleRate > 0) {
            $song['sample_rate'] = $audio->sampleRate;
        }
        if (isset($audio->isVbr)) {
            $song['vbr'] = $audio->isVbr;
        }
        if (isset($audio->channel)) {
            $song['channel'] = $audio->channel;
        }
        if (isset($audio->hasCover)) {
            $song['cover'] = $audio->hasCover;
        }

        // insert song into database
        $song = Song::create($song);
        Log::channel('lib')->debug(
            "inserted track '$song->name' of artist '$artistName', album '".$audio->tags['album']."'"
        );
    }

    /**
     * @function return id of existing artist or the newly created artist
     * @param string $name
     * @return string Artist->id
     */
    public function getArtist(string $name):string
    {
        $artist = Artist::firstOrCreate(['name' => $name]);
        if ($artist->wasRecentlyCreated) {
            Log::channel('lib')->debug("inserted artist '$artist->name'");
        }
        return $artist->id;
    }

    /**
     * @function return id of existing album or the newly created album
     * @param string $name
     * @param string $artistId
     * @param string $artistName
     * @param int $year
     * @return string Album->id
     */
    public function getAlbum(string $name, string $artistId, string $artistName, int $year):string
    {
        $album = Album::firstOrCreate([
            'album_artist_id' => $artistId,
            'name' => $name,
        ]);
        if ($album->wasRecentlyCreated) {
            Log::channel('lib')->debug("inserted album '$album->name' of artist '$artistName'");
            $album->year = $year; // only update the year for the song that created the album. we don't want multiple "Kill Bill" Albums.
            $album->save();
        }

        return $album->id;
    }

    /**
     * @function return id of existing genre or the newly created genre
     * @param string $name
     * @return string
     */
    public function getGenre(string $name):string
    {
        $genre = Genre::firstOrCreate(['name' => $name]);
        if ($genre->wasRecentlyCreated) {
            Log::channel('lib')->debug("inserted genre '$genre->name'");
        }
        return $genre->id;
    }

}
