<?php

namespace App\Models;

class ResearchRecord extends BaseModel
{
    public function department() { return $this->belongsTo(Department::class); }
}
