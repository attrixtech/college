<?php

namespace App\Models;

class Hostel extends BaseModel
{
    public function warden() { return $this->belongsTo(Employee::class, "warden_id"); }
    public function rooms() { return $this->hasMany(HostelRoom::class); }
    public function allocations() { return $this->hasMany(HostelAllocation::class); }
    public function activities() { return $this->hasMany(HostelActivity::class); }
}
