<?php

namespace App\Models;

class College extends BaseModel
{
    public function campus() { return $this->belongsTo(Campus::class); }
    public function departments() { return $this->hasMany(Department::class); }
}
