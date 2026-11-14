<?php

namespace App\Models;

class AccreditationRecord extends BaseModel
{
    public function department() { return $this->belongsTo(Department::class); }
}
