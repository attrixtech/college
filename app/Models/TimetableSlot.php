<?php

namespace App\Models;

class TimetableSlot extends BaseModel
{
    public function classSection() { return $this->belongsTo(ClassSection::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function faculty() { return $this->belongsTo(Employee::class, "faculty_id"); }
    public function substituteFaculty() { return $this->belongsTo(Employee::class, "substitute_faculty_id"); }
}
