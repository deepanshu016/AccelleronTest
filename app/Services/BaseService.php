<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

abstract class BaseService
{
    protected $model;
    protected $cacheTime = 600; // 10 minutes
    protected $cachePrefix = '';

    public function __construct()
    {
        if (!$this->model) {
            throw new \Exception('Model property must be defined in child repository.');
        }
    }

    protected function getCacheKey($method, $id = null)
    {
        return $this->cachePrefix . '_' . $method . ($id ? '_' . $id : '');
    }

    public function all($relations=[])
    {
        return Cache::remember($this->getCacheKey('all'), $this->cacheTime, function () use($relations){
           return $this->model::with($relations)->latest();
        });
    }


    public function find($id,$relations=[])
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function create(array $data)
    {
        $result = $this->model::create($data);
        $this->clearCache();
        return $result;
    }

    public function update($id,array $data)
    {
        $item = $this->model::findOrFail($id);
        $item->update($data);
        $this->clearCache();
        return $item;
    }

    public function delete($id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();
        $item->refresh();
        $this->clearCache();
        return $item->trashed();
    }

    public function count($condition)
    {
        return $this->model::where($condition)->count();
    }

    public function findByCondition($condition)
    {
        return $this->model::where($condition)->first();
    }
    public function findByOrderWithCondition($condition,$relations = [],$order_by,$order_by_column)
    {
        return $this->model::with($relations)->where($condition)->orderBy($order_by_column,$order_by)->first();
    }
    protected function clearCache()
    {
        Cache::forget($this->getCacheKey('all'));
    }
}
