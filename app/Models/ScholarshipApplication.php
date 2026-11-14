<?php

namespace App\Models;

class ScholarshipApplication extends BaseModel
{
    public function scholarship() { return $this->belongsTo(Scholarship::class); }
    public function student() { return $this->belongsTo(Student::class); }
}
