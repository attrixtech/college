<?php

namespace App\Models;

class HostelActivity extends BaseModel
{
    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function student() { return $this->belongsTo(Student::class); }
}
