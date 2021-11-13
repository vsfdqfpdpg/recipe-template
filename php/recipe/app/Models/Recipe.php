<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['name', 'preserve', 'image', 'description', 'duration', 'cooking_style', 'category', 'status', 'user_id'];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, "favourite");
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, "comment");
    }
}
