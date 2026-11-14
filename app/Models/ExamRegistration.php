<?php

namespace App\Models;

class ExamRegistration extends BaseModel
{
    public function exam() { return $this->belongsTo(Exam::class); }
    public function student() { return $this->belongsTo(Student::class); }
}
