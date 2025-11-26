<?php

namespace App\Console\Commands;

use App\Services\FormatService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class CreateAudiobookList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:csv:audiobook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a csv list of audiobook files.';

    /**
     * @function create csv file with music list
     * @return void
     */
    private function createCSV():void
    {
        $result = Process::run(
            'find '.config('collection.server.audiobooks.path')
            .' -type f -name "*.mp3" -follow -print > '
            .config('collection.server.audiobooks.path')
            .config('collection.server.audiobooks.csv')
        );
        if ($result->successful()) {
            Log::channel('lib')->info(
                "created/updated '".config('collection.server.audiobooks.csv')."' file."
            );
            $this->info("created/updated '".config('collection.server.audiobooks.csv')."' file.");
        }
    }

    /**
     * @function Execute the console command.
     * @param FormatService $fd
     * @return void
     */
    public function handle(FormatService $fd)
    {
        $start = now();
        $this->comment("artisan command 'app:csv:audiobook' started.");
        Log::channel('lib')->info("=======================================================");
        Log::channel('lib')->info("artisan command 'app:csv:audiobook' started.");
        Log::channel('lib')->info("=======================================================");
        $this->createCSV();
        $ms = $start->diffInMilliseconds(now());
        Log::channel('lib')->info("=======================================================");
        Log::channel('lib')->info("artisan command 'app:csv:audiobook' finished in ".$fd->formatMs($ms).".");
        Log::channel('lib')->info("=======================================================");
        $this->comment("artisan command 'app:csv:audiobook' finished in ".$fd->formatMs($ms).".");
    }
}
