<?php

namespace App\Jobs;

use App\Notifications\ManageWaitingListNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class RecentUserInWaitingJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels,Dispatchable;
    protected $event, $booking;
    /**
     * Create a new job instance.
     */
    public function __construct($event,$booking)
    {
        $this->event = $event;
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('RecentUserInWaitingJob started for event ID: ' . $this->event->id);
        DB::transaction(function () {
            $recentUserWithInWaiting = $this->booking->findByOrderWithCondition(
                [['booking_status','=','waiting'],['event_id','=',$this->event->id]],
                ['user','event'],
                'DESC',
                'created_at'
            );

            if ($recentUserWithInWaiting) {
                Log::info('Found waiting booking:', ['booking_id' => json_decode($recentUserWithInWaiting)]);
                $confirmedTicket  = $this->booking->count([['event_id','=',$this->event->id],['booking_status','=','confirmed']]);
                // Log::info('Capacity', ['confirmedTicket' => $confirmedTicket]);
                // Log::info('Capacity', ['capacity' => $this->event->capacity]);

                if($confirmedTicket < $this->event->capacity){
                    $recentUserWithInWaiting->update([
                        'booking_status' => 'confirmed'
                    ]);
                    Log::info('Booking status updated to confirmed for booking ID: ' . $recentUserWithInWaiting->id);
                    $recentUserWithInWaiting->user->notify(new ManageWaitingListNotification($recentUserWithInWaiting));
                    Log::info('Notification dispatched to user ID: ' . $recentUserWithInWaiting->user->id);
                }
            }else{
                Log::info('No waiting bookings found for event ID: ' . $this->event->id);
            }
        });
        Log::info('RecentUserInWaitingJob completed for event ID: ' . $this->event->id);
    }
}
