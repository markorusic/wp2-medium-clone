<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
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

    public function activities() {
        return $this->hasMany('App\Models\UserActivity');
    }

     public function follow() {
        $follower_id = auth()->id();
        if (is_null($follower_id)) {
            return false;
        }
        if ($this->isFollowing()) {
            return $this->followers()->detach($follower_id);
        }
        return $this->followers()->attach($follower_id);
    }

    public function isFollowing() {
        $follower_id = auth()->id();
        return $this->followers()
            ->where(compact('follower_id'))
            ->exists();
    }

    public function track($activity) {
        if (is_array($activity)) {
            $activities = collect($activity)
                ->map(function ($activity) {
                    return compact('activity');
                })
                ->toArray();
            return $this->activities()->createMany($activities);
        }
        return $this->activities()->create(compact('activity'));
    }
}
