<?php

namespace App\Models;

class StudentAttendance extends BaseModel
{
    public function student() { return $this->belongsTo(Student::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function classSection() { return $this->belongsTo(ClassSection::class); }
}
