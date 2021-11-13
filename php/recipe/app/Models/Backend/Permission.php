<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ["title", "slug", "description", "active"];

    public function roles()
    {
        return $this->belongsToMany(Role::class, "permission_roles");
    }
}
