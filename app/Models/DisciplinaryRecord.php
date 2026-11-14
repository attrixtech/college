<?php

namespace App\Models;

class DisciplinaryRecord extends BaseModel
{
    public function student() { return $this->belongsTo(Student::class); }
}
