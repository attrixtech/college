<?php

namespace App\Models;

class Payroll extends BaseModel
{
    public function employee() { return $this->belongsTo(Employee::class); }
}
