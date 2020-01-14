<?php

namespace App\Utils;

use Illuminate\Support\Str;

trait FilterableModel
{
    public function scopeFilter($query, $filters) {
        $filters = collect($filters);
        $order = $filters->get('order');
        $search = $filters->except(['order', 'page']);

        $search->keys()->each(function ($key) use ($search, $query) {
            $value = $search->get($key);
            if (is_array($value)) {
                $query->whereHas($key, function ($innerQuery) use ($value) {
                    $innerQuery->whereIn('id', explode(',', $value[0]));
                });
            } else {
                $query->where($key, 'like', '%' . $value . '%');
            }
        });

        if ($order) {
            $orderTuple = explode(',', $order);
            $query->orderBy($orderTuple[0], $orderTuple[1] ?? 'asc');
        }

        return $query;
    }
}
