<?php

namespace App\Console\Commands;

use App\Models\GlobalProperties;
use Illuminate\Console\Command;

class UpdateEverything extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Full update of music and audiobooks.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // clean public storage
        $this->call('app:clean');
        // create music csv list
        $this->call('app:csv:music');
        sleep(2);
        // update music database
        $this->call('app:db:music');
        sleep(5);
        // create audiobook csv list
        $this->call('app:csv:audiobook');
        sleep(2);
        // update audiobook database
        $this->call('app:db:audiobook');
        $prop = GlobalProperties::where('key', 'refresh.full')->first();
        $prop->updated_at = now();
        $prop->save();
    }
}
