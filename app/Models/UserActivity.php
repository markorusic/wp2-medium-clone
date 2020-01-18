<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\OrderScope;

class UserActivity extends Model
{
    protected $fillable = ['type', 'description'];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new OrderScope);
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
