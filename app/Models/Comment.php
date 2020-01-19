<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\{OrderScope, FilterableModel};

class Comment extends Model
{

    use FilterableModel;

    protected $fillable = ['content', 'user_id', 'post_id'];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new OrderScope);
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function post() {
        return $this->belongsTo('App\Models\Post');
    }
}
