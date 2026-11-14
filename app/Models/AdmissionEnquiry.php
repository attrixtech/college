<?php

namespace App\Models;

class AdmissionEnquiry extends BaseModel
{
    public function course() { return $this->belongsTo(Course::class); }
    public function counsellor() { return $this->belongsTo(User::class, "counsellor_id"); }
    public function followups() { return $this->hasMany(EnquiryFollowup::class); }
}
