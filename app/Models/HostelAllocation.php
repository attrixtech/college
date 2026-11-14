<?php

namespace App\Models;

class HostelAllocation extends BaseModel
{
    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function room() { return $this->belongsTo(HostelRoom::class, "hostel_room_id"); }
    public function student() { return $this->belongsTo(Student::class); }
}
