<?php

namespace App\Services;

use App\Models\EventBooking;
use App\Repositories\Contracts\EventBookingRepositoryInterface;

class EventBookingService extends BaseService implements EventBookingRepositoryInterface
{
    protected $model;
    protected $cachePrefix = 'bookings';


    public function __construct()
    {
        $this->model = new EventBooking();
        parent::__construct();
    }
}
