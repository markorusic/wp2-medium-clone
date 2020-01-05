<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = ['title', 'content'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }

    public function likes() {
        return $this->hasMany('App\Models\Like');
    }

    public function categories() {
        return $this->belongsToMany('App\Models\Category');
    }

    public function like() {
        if ($this->isLiked()) {
            return $this->likes()->where([
                'user_id' => auth()->id()
            ])->delete();
        }
        return $this->likes()->create([
            'user_id' => auth()->id()
        ]);
    }

    public function isLiked() {
        return $this->likes()->where([
            'user_id' => auth()->id()
        ])->exists();
    }
}
