<?php

namespace App\Models;

class Role extends BaseModel
{
    public function permissions() { return $this->belongsToMany(Permission::class, "role_permission"); }
    public function users() { return $this->hasMany(User::class); }
}
