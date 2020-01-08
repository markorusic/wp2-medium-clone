<?php

namespace App\Utils;

trait ModelTextSearch
{
    static function textFilter($param) {
        $value = request()->input($param);
        return self
            ::where($param, 'like', '%' . $value . '%' )
            ->paginate();
    }
}
 