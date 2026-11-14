<?php

namespace App\Models;

class EmployeeAttendance extends BaseModel
{
    public function employee() { return $this->belongsTo(Employee::class); }
}
