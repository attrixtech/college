<?php

namespace App\Models;

class EmployeeEvaluation extends BaseModel
{
    public function employee() { return $this->belongsTo(Employee::class); }
}
