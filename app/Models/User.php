<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Utils\{FilterableModel};

class User extends Authenticatable
{
    use Notifiable, FilterableModel;

    protected $fillable = [
        'name', 'email', 'password', 'bio', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts() {
        return $this->hasMany('App\Models\Post');
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

    public function followers() {
        return $this->belongsToMany('App\Models\User', 'followers', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function following() {
        return $this->belongsToMany('App\Models\User', 'followers', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function activities() {
        return $this->hasMany('App\Models\UserActivity');
    }

     public function follow() {
        $follower_id = auth()->id();
        if ($this->isFollowing()) {
            return $this->followers()->detach($follower_id);
        }
        return $this->followers()->attach($follower_id);
    }

    public function isFollowing() {
        $follower_id = auth()->id();
        if ($this->followers) {
            return $this->followers->contains('id', $follower_id);
        }
        return $this->followers()
            ->where(compact('follower_id'))
            ->exists();
    }

    public function track($activity_type, $activity_description = null) {
        return $this->activities()->create([
            'type' => $activity_type,
            'description' => $activity_description
        ]);
    }
}
