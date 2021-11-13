<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected static function boot()
    {
        static::deleting(function ($comment) {
            $comment->favourites()->delete();
            $comment->comments()->get()->each->delete();
            $comment->comments()->delete();
        });

        parent::boot();
    }

    protected $fillable = ["user_id", "comment"];

    public function favourites()
    {
        return $this->morphMany(Favourite::class, "favourite");
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, "comment");
    }

    public function comment()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
