<?php

namespace App\Models;

class EnquiryFollowup extends BaseModel
{
    public function enquiry() { return $this->belongsTo(AdmissionEnquiry::class, "admission_enquiry_id"); }
    public function user() { return $this->belongsTo(User::class); }
}
