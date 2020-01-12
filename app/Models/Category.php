<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'main_photo'];

    public function posts() {
    return $this->belongsToMany('App\Models\Post');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User');
    }
}
