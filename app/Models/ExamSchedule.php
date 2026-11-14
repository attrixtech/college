<?php

namespace App\Models;

class ExamSchedule extends BaseModel
{
    public function exam() { return $this->belongsTo(Exam::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function invigilator() { return $this->belongsTo(Employee::class, "invigilator_id"); }
}
