<?php

namespace App\Repositories\Contracts;

interface EventRepositoryInterface
{
    public function all($relations=[]);
    public function find($id,$relations=[]);
    public function create(array $data);
    public function update($id,array $data);
    public function delete($id);
}
