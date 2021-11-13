<?php

namespace App\Models;

use App\Models\Backend\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, "role_users");
    }

    public function hasRole($role)
    {
        $exit = Role::where('title', $role)->first();
        if (!$exit) return false;
        return $this->belongsToMany(Role::class, "role_users")->wherePivot("role_id", $exit->id)->exists();
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, "favourite");
    }
}
