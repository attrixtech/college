<?php

namespace App\Models;

class Announcement extends BaseModel
{
    public function department() { return $this->belongsTo(Department::class); }
    public function creator() { return $this->belongsTo(User::class, "created_by"); }
}
