<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class EventBooking extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','event_id','booking_status'];



    public function user() {
       return $this->belongsTo(User::class);
    }


    public function event() {
        return $this->belongsTo(Event::class);
    }
}
