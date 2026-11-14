<?php

namespace App\Models;

class AcademicCalendar extends BaseModel
{
    public function academicSession() { return $this->belongsTo(AcademicSession::class); }
}
