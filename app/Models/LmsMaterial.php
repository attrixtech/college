<?php

namespace App\Models;

class LmsMaterial extends BaseModel
{
    public function subject() { return $this->belongsTo(Subject::class); }
    public function faculty() { return $this->belongsTo(Employee::class, "faculty_id"); }
}
