<?php
namespace App\Services;

use App\Models\Audiobook;
use App\Models\Author;
use App\Models\Narrator;
use App\Models\Track;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use wapmorgan\Mp3Info\Mp3Info;

class AudiobookLibraryService {

    /**
     * @function main function that renews the database
     * @return bool
     * @throws \Exception
     */
    public function renewDatabase(): bool
    {
        Log::channel('lib')->info("starting function AudiobookLibraryService->renewDatabase()");
        $lib = new LibraryService;
        $list = $lib->getFileList(
            config('collection.server.audiobooks.csv'),
            config('collection.server.audiobooks.path')
        );
        if (count($list) > 0) {
            Log::channel('lib')->debug("found ".count($list)." files.");
            // empty database
            $this->pruneDB();
            // main loop over the file list
            $list->each( function (string $file) {
                $this->insertTrack($file);
            });
            Log::channel('lib')->info("finished audiobooks database renewal with no errors.");
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
        Audiobook::truncate();
        Log::channel('lib')->info("table 'audiobooks' truncated.");
        Author::truncate();
        Log::channel('lib')->info("table 'authors' truncated.");
        Narrator::truncate();
        Log::channel('lib')->info("table 'narrators' truncated.");
        Track::truncate();
        Log::channel('lib')->info("table 'tracks' truncated.");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Log::channel('lib')->info("audiobooks db tables truncated.");
    }

    /**
     * @function analyze file, then insert audiobook track into db
     * @param string $path
     * @return void
     * @throws \Exception
     */
    public function insertTrack(string $path): void
    {
        $full_path = config('collection.server.audiobooks.path') . $path;
        $lib = new LibraryService;
        Log::channel('lib')->info("analyzing '" . $full_path . "'");

        // call Mp3Info library
        $audio = new Mp3Info($full_path, true);
        $modifiedAt = filemtime($full_path);
        $authorId = null;
        $authorName = "n/a";
        $narratorId = null;
        $narratorName = "n/a";

        // prepare author, narrator and audiobook, and determine the corresponding ids
        if (array_key_exists('TCOM', $audio->tags2)) {
            $authorId = $this->getAuthor($audio->tags2['TCOM']) ?? null;
            $authorName = $audio->tags2['TCOM'];
        }
        if (array_key_exists('artist', $audio->tags)) {
            $narratorId = $this->getNarrator($audio->tags['artist']) ?? null;
            $narratorName = $audio->tags['artist'];
        }
        $audiobookId = $this->getAudiobook(
            $audio->tags['album'],
            array_key_exists('year', $audio->tags1) ? (int)$audio->tags1['year'] : null
        );

        // fields that we should have in the mp3 tags
        $track = [
            'name' => $audio->tags['song'],
            'author_id' => $authorId,
            'narrator_id' => $narratorId,
            'audiobook_id' => $audiobookId,
            'publisher' => $audio->tags2['TPUB'] ?? null,
            'composer' => $audio->tags2['TCOM'] ?? null,
            'path' => $path,
        ];

        // fields that we might not have in the mp3 tags
        if (array_key_exists('track', $audio->tags)) {
            $track['track'] = $lib->getNum($audio->tags['track']);
        }
        if (isset($audio->tags2) && array_key_exists('TPOS', $audio->tags2)) {
            $track['disc'] = $lib->getNum($audio->tags2['TPOS']);
        }
        if (isset($audio->codecVersion) && isset($audio->layerVersion)) {
            $track['codec'] = "MPEG $audio->codecVersion Layer $audio->layerVersion";
        }
        if (isset($audio->_fileSize) && $audio->_fileSize > 0) {
            $track['size'] = $audio->_fileSize;
        }
        if (isset($audio->duration) && $audio->duration > 0) {
            $track['duration'] = $audio->duration;
        }
        if ($modifiedAt) {
            $track['modified_at'] = Carbon::createFromTimestamp($modifiedAt);
        }
        if (isset($audio->sampleRate) && $audio->sampleRate > 0) {
            $track['sample_rate'] = $audio->sampleRate;
        }
        if (isset($audio->isVbr)) {
            $track['vbr'] = $audio->isVbr;
        }
        if (isset($audio->channel)) {
            $track['channel'] = $audio->channel;
        }
        if (isset($audio->hasCover)) {
            $track['cover'] = $audio->hasCover;
        }

        // insert track into database
        $newTrack = Track::create($track);
        Log::channel('lib')->debug(
            "inserted track '$newTrack->name' of author '$authorName', audiobook '".$audio->tags['album']."', "
            ."narrator '".$narratorName."'"
        );

    }

    /**
     * @function return id of existing author or the newly created author
     * @param string $name
     * @return string Author->id
     */
    private function getAuthor(string $name):string
    {
        $author = Author::firstOrCreate(['name' => $name]);
        if ($author->wasRecentlyCreated) {
            Log::channel('lib')->debug("inserted author '$author->name'");
        }
        return $author->id;
    }

    /**
     * @function return id of existing narrator or the newly created narrator
     * @param string $name
     * @return string Narrator->id
     */
    private function getNarrator(string $name):string
    {
        $narrator = Narrator::firstOrCreate(['name' => $name]);
        if ($narrator->wasRecentlyCreated) {
            Log::channel('lib')->debug("inserted narrator '$narrator->name'");
        }
        return $narrator->id;
    }

    /**
     * @function return id of existing audiobook or the newly created audiobook
     * @param string $name
     * @param int|null $year
     * @return string
     */
    private function getAudiobook(string $name, int|null $year):string {
        $audiobook = Audiobook::firstOrCreate([
            'name' => $name
        ]);
        if ($audiobook->wasRecentlyCreated) {
            $audiobook->year = $year;
            $audiobook->save();
            Log::channel('lib')->debug("inserted audiobook '$audiobook->name'");
        }
        return $audiobook->id;
    }

}
