<?php

namespace App\Models;

class StudentFeeAccount extends BaseModel
{
    public function student() { return $this->belongsTo(Student::class); }
    public function feeStructure() { return $this->belongsTo(FeeStructure::class); }
    public function academicSession() { return $this->belongsTo(AcademicSession::class); }
    public function payments() { return $this->hasMany(FeePayment::class); }
}
