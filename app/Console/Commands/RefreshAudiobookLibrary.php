<?php

namespace App\Console\Commands;

use App\Models\GlobalProperties;
use App\Services\FormatService;
use App\Services\AudiobookLibraryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshAudiobookLibrary extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db:audiobook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh audiobook database now';

    /**
     * Execute the console command.
     * @param AudiobookLibraryService $a
     * @param FormatService $fd
     * @return int
     * @throws \Exception
     */
    public function handle(AudiobookLibraryService $a, FormatService $fd): int
    {
        $start = now();
        $this->comment("artisan command 'app:db:audiobook' started.");
        Log::channel('lib')->info("=======================================================");
        Log::channel('lib')->info("artisan command 'app:db:audiobook' started.");
        Log::channel('lib')->info("=======================================================");
        $a->renewDatabase();
        $prop = GlobalProperties::where('key', 'refresh.audiobooks')->first();
        $prop->updated_at = now();
        $prop->save();
        $ms = $start->diffInMilliseconds(now());
        Log::channel('lib')->info("=======================================================");
        Log::channel('lib')->info("finished artisan command 'app:db:audiobook' in ".$fd->formatMs($ms).".");
        Log::channel('lib')->info("=======================================================");
        $this->comment("finished artisan command 'app:db:audiobook' in ".$fd->formatMs($ms).".");

        return 0;
    }

}
