<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;

class EventService extends BaseService implements EventRepositoryInterface
{
    protected $model;
    protected $cachePrefix = 'events';


    public function __construct()
    {
        $this->model = new Event();
        parent::__construct();
    }
}
