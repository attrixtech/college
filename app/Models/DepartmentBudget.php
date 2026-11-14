<?php

namespace App\Models;

class DepartmentBudget extends BaseModel
{
    public function department() { return $this->belongsTo(Department::class); }
}
