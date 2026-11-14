<?php

namespace App\Models;

class Subject extends BaseModel
{
    public function course() { return $this->belongsTo(Course::class); }
}
