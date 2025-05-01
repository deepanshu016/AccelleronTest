<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService extends BaseService implements UserRepositoryInterface
{
    protected $model;
    protected $cachePrefix = 'users';


    public function __construct()
    {
        $this->model = new User();
        parent::__construct();
    }
}
