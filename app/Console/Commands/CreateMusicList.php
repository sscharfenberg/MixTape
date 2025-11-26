<?php

namespace App\Console\Commands;

use App\Services\FormatService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class CreateMusicList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:csv:music';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a csv list of music files.';

    /**
     * @function create csv file with music list
     * @return void
     */
    private function createCSV():void
    {
        $result = Process::run(
            'find '.config('collection.server.music.path')
            .' -type f -name "*.mp3" -follow -print > '
            .config('collection.server.music.path')
            .config('collection.server.music.csv')
        );
        if ($result->successful()) {
            Log::channel('lib')->info(
                "created/updated '".config('collection.server.music.csv')."' file."
            );
            $this->info("created/updated '".config('collection.server.music.csv')."' file.");
        }
    }

    /**
     * @function Execute the console command.
     * @param FormatService $fd
     * @return void
     */
    public function handle(FormatService $fd): void
    {
        $start = now();
        $this->comment("artisan command 'app:csv:music' started.");
        Log::channel('lib')->info("=======================================================");
        Log::channel('lib')->info("artisan command 'app:csv:music' started.");
        Log::channel('lib')->info("=======================================================");
        $this->createCSV();
        $ms = $start->diffInMilliseconds(now());
        Log::channel('lib')->info("=======================================================");
        Log::channel('lib')->info("artisan command 'app:csv:music' finished in ".$fd->formatMs($ms).".");
        Log::channel('lib')->info("=======================================================");
        $this->comment("artisan command 'app:csv:music' finished in ".$fd->formatMs($ms).".");
    }

}
