<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
class ManageExpiredEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:manage-expired-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to delete all expired events from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredEvents = Event::where('date', '<', now())->get();
        if ($expiredEvents->isEmpty()) {
            $this->info('There is expired events found in the record....');
            return;
        }

        $count = $expiredEvents->count();

        Event::where('date', '<', now())->delete();

        $this->info("Total $count expired events cleared from database.....");
    }
}
