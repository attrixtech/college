<?php

namespace App\Models;

class SubjectAllocation extends BaseModel
{
    public function academicSession() { return $this->belongsTo(AcademicSession::class); }
    public function classSection() { return $this->belongsTo(ClassSection::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function faculty() { return $this->belongsTo(Employee::class, "faculty_id"); }
}
