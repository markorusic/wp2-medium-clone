<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\{FilterableModel, OrderScope};

class Category extends Model
{
    use FilterableModel;

    protected $fillable = ['name', 'description', 'main_photo'];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new OrderScope);
    }

    public function posts() {
        return $this->belongsToMany('App\Models\Post');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User');
    }
}
