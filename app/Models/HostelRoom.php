<?php

namespace App\Models;

class HostelRoom extends BaseModel
{
    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function allocations() { return $this->hasMany(HostelAllocation::class); }
}
