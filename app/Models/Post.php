<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\{FilterableModel, OrderScope};

class Post extends Model
{

    use FilterableModel;

    protected $fillable = ['title', 'content', 'description', 'main_photo'];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new OrderScope);
    }

    public function scopePopular($query) {
        $query
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->where('created_at', '>=', now()->subDays(15)->toDateTimeString());
    }

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
        return $this->likes()
            ->where(compact('user_id'))
            ->exists();
    }
}
