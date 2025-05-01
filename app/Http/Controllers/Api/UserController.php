<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Repositories\Contracts\EventBookingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Http\Requests\EventBookingRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use Exception;
class UserController extends Controller
{
    protected $user;
    protected $event;
    protected $booking;


    public function __construct(
        EventBookingRepositoryInterface $booking,
        UserRepositoryInterface $user,
        EventRepositoryInterface $event
    )
    {
        $this->booking = $booking;
        $this->event = $event;
        $this->user = $user;
    }
    // Create User Bookings for Event
    public function eventBooking(EventBookingRequest $request){
        try{
            $bookingData = DB::transaction(function () use ($request) {
                $user = $this->user->findByCondition([['email','=',$request->email]]);
                if(!$user){
                    $request->merge(['password' => '123456']);
                    $user = $this->user->create($request->only(['first_name', 'last_name', 'email', 'phone', 'password']));
                }
                $request->merge(['user_id' => $user->id]);
                $checkDuplicate = $this->booking->count([['user_id','=',$request->user_id],['event_id','=',$request->event_id]]);
                if($checkDuplicate > 0){
                    throw new \Exception('User already registered for this event');
                }
                $event = Event::find($request->event_id);
                $confirmedTicket  = $this->booking->count([['event_id','=',$request->event_id],['booking_status','=','confirmed']]);

                $bookingStatus = $confirmedTicket < $event->capacity ? 'confirmed' : 'waiting';
                $request->merge(['booking_status'=>$bookingStatus]);
                $booking = $this->booking->create($request->only(['event_id','user_id','booking_status']));
                return $this->booking->find($booking->id, ['user', 'event']);
            });
            if(!$bookingData){
                return ApiResponse::success([],false,'Something went wrong !!!');
            }
            return ApiResponse::success($bookingData,true,'Registration successfull !!!');
        }catch(\Exception $e){
            return ApiResponse::error('Something went wrong',500,$e->getMessage());
        }
    }
}
