<?php

namespace App\Models;

class SalaryStructure extends BaseModel
{
    public function employee() { return $this->belongsTo(Employee::class); }
}
