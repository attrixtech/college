<?php

namespace App\Models;

class AdmissionApplication extends BaseModel
{
    protected $casts = ["documents_json" => "array"];
    public function course() { return $this->belongsTo(Course::class); }
    public function academicSession() { return $this->belongsTo(AcademicSession::class); }
}
