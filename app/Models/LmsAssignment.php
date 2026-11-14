<?php

namespace App\Models;

class LmsAssignment extends BaseModel
{
    public function subject() { return $this->belongsTo(Subject::class); }
    public function faculty() { return $this->belongsTo(Employee::class, "faculty_id"); }
    public function submissions() { return $this->hasMany(LmsSubmission::class); }
}
