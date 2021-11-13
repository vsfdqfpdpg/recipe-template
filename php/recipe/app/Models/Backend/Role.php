<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ["title", "description", "slug", "active"];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "permission_roles");
    }
}
