<?php

namespace App\Models;

class AttendanceCorrection extends BaseModel
{
    public function user() { return $this->belongsTo(User::class); }
}
