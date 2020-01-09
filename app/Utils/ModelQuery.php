<?php

namespace App\Utils;

trait ModelQuery
{
    public function scopeQueryDsl($query) {
        $order = request()->input('order');
        $search = collect(request()->all())->except(['order', 'page', 'size']);

        if ($search->count() > 0) {
            $searchArr = $search->keys()->map(function ($key) use ($search) {
                return [
                    $key, 'like', '%' . $search->get($key) . '%'
                ];
            })->toArray();
            $query->where($searchArr);
        }

        if ($order) {
            $orderTuple = explode(',', $order);
            $query->orderBy($orderTuple[0], $orderTuple[1] ?? 'asc');
        }

        return $query->paginate();
    }
}
 