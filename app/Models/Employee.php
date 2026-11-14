<?php

namespace App\Models;

class Employee extends BaseModel
{
    public function user() { return $this->belongsTo(User::class); }
    public function college() { return $this->belongsTo(College::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function salaryStructure() { return $this->hasOne(SalaryStructure::class); }
    public function leaves() { return $this->hasMany(EmployeeLeave::class); }
    public function evaluations() { return $this->hasMany(EmployeeEvaluation::class); }
    public function payrolls() { return $this->hasMany(Payroll::class); }
    public function subjectAllocations() { return $this->hasMany(SubjectAllocation::class, "faculty_id"); }
}
