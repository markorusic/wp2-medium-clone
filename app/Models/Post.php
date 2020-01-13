<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\ModelQuery;

class Post extends Model
{

    use ModelQuery;

    protected $fillable = ['title', 'content', 'description', 'main_photo'];

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

    public function comment($content) {
        $user_id = auth()->id();
        return $this->comments()->create(compact('user_id', 'content'));
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
        $user_id = auth()->id();
        if ($this->likes) {
            return $this->likes->contains('user_id', $user_id);
        }
        if ($user_id) {
            return $this->likes()
                ->where(compact('user_id'))
                ->exists();
        }
        return false;
    }
}
