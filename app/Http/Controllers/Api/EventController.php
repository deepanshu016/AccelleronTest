<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Repositories\Contracts\EventBookingRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Jobs\RecentUserInWaitingJob;
use App\Http\Requests\EventRequest;
use App\Models\EventBooking;
use Exception;
class EventController extends Controller
{
    protected $event;
    protected $booking;


    public function __construct(
        EventRepositoryInterface $event,
        EventBookingRepositoryInterface $booking
    )
    {
        $this->event = $event;
        $this->booking = $booking;
    }


    public function index(Request $request){
        // try{
           $eventList = $this->event->all(['bookings']);
           if($eventList->isEmpty()){
            return ApiResponse::success([],false,'No Data found !!!');
           }
           return ApiResponse::success($eventList,true,'Event Fetched Successfully');
        // }catch(Exception $e){
        //     return ApiResponse::error('Something went wrong',500,$e->getMessage());
        // }
    }
    // Create Event
    public function store(EventRequest $request){
        try{
            $event = $this->event->create($request->all());
            if(!$event){
                return ApiResponse::success([],false,'Something went wrong');
            }
            return ApiResponse::success($event,true,'New Event added successfully !!!');
        }catch(Exception $e){
            return ApiResponse::error('Something went wrong',500,$e->getMessage());
        }
    }
    // Update Event
    public function update($id,EventRequest $request){
        try{
            $event = $this->event->update($id, $request->toArray());
            if(!$event){
                return ApiResponse::success([],false,'Something went wrong');
            }
            return ApiResponse::success($event,true,'Event updated successfully !!!');
        }catch(Exception $e){
            return ApiResponse::error('Something went wrong',500,$e->getMessage());
        }
    }
    // Delete Event
    public function delete($id){
        try{
            $result = $this->event->delete($id);
            if(!$result){
                return ApiResponse::success([],false,'Something went wrong');
            }
            return ApiResponse::success($result,true,'Event deleted successfully !!!');
        }catch(Exception $e){
            return ApiResponse::error('Something went wrong',500,$e->getMessage());
        }
    }


    // Cancel Ticket from  Event
    public function cancelEventTicket($ticket_id){
        try{
            $ticketDetail = $this->booking->find($ticket_id,['user','event']);
            $ticketDetail->delete();
            if($ticketDetail->booking_status === 'confirmed'){
                RecentUserInWaitingJob::dispatch($ticketDetail->event, $this->booking);
            }
            return ApiResponse::success($ticketDetail,true,'Your ticket has been cancelled successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Sorry, Ticket not found', 404);
        } catch(Exception $e){
            return ApiResponse::error('Something went wrong',500,$e->getMessage());
        }
    }
}
