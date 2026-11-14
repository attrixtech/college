<?php

namespace App\Models;

class StudentDocument extends BaseModel
{
    public function student() { return $this->belongsTo(Student::class); }
}
