<?php

namespace App\Models;

class Alumnus extends BaseModel
{
    protected $table = "alumni";
    public function student() { return $this->belongsTo(Student::class); }
    public function course() { return $this->belongsTo(Course::class); }
}
