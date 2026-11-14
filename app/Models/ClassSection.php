<?php

namespace App\Models;

class ClassSection extends BaseModel
{
    public function course() { return $this->belongsTo(Course::class); }
    public function classTeacher() { return $this->belongsTo(Employee::class, "class_teacher_id"); }
    public function timetableSlots() { return $this->hasMany(TimetableSlot::class); }
    public function students() { return $this->hasMany(Student::class); }
}
