<?php

namespace App\Console\Commands;

use App\Models\GlobalProperties;
use App\Services\FormatService;
use App\Services\MusicLibraryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshMusicLibrary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db:music';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh music database now';

    /**
     * Execute the console command.
     * @param MusicLibraryService $m
     * @param FormatService $fd
     * @return int
     * @throws \Exception
     */
    public function handle(MusicLibraryService $m, FormatService $fd): int
    {
        $start = now();
        $this->comment("artisan command 'app:db:music' started.");
        Log::channel('lib')->info("=======================================================");
        Log::channel('lib')->info("artisan command 'app:db:music' started.");
        Log::channel('lib')->info("=======================================================");
        $m->renewDatabase();
        $prop = GlobalProperties::where('key', 'refresh.music')->first();
        $prop->updated_at = now();
        $prop->save();
        $ms = $start->diffInMilliseconds(now());
        Log::channel('lib')->info("=======================================================");
        Log::channel('lib')->info("finished artisan command 'app:db:music' in ".$fd->formatMs($ms).".");
        Log::channel('lib')->info("=======================================================");
        $this->comment("finished artisan command 'app:db:music' in ".$fd->formatMs($ms).".");

        return 0;
    }
}
