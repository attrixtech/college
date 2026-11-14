<?php

namespace App\Models;

class LmsSubmission extends BaseModel
{
    public function assignment() { return $this->belongsTo(LmsAssignment::class, "lms_assignment_id"); }
    public function student() { return $this->belongsTo(Student::class); }
}
