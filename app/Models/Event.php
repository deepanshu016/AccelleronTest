<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Event extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'title',
        'description',
        'date',
        'venue',
        'capacity',
        'vip_ticket_price',
        'is_recurring_event',
        'recurring_type',
    ];


    protected static function booted()
    {
        parent::booted();

        static::deleting(function ($event) {
            $event->bookings()->each(function ($booking) {
                $booking->delete();
            });
        });
    }

    public function bookings(){
        return $this->hasMany(EventBooking::class);
    }
}
