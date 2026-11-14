<?php

namespace App\Models;

class AcademicSession extends BaseModel
{
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
}
