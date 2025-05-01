<?php

namespace App\Repositories\Contracts;

interface EventBookingRepositoryInterface
{
    public function all($relations=[]);
    public function create(array $data);
    public function find($id,$relations=[]);
    public function update($id,array $data);
    public function count($condition);
    public function findByCondition($condition);
    public function findByOrderWithCondition($condition, $relations=[], $order_by, $order_by_column);
}
