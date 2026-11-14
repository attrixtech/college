<?php

namespace App\Models;

class EmployeeLeave extends BaseModel
{
    public function employee() { return $this->belongsTo(Employee::class); }
    public function approver() { return $this->belongsTo(User::class, "approved_by"); }
}
