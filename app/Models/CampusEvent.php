<?php

namespace App\Models;

class CampusEvent extends BaseModel
{
    public function department() { return $this->belongsTo(Department::class); }
    public function registrations() { return $this->hasMany(EventRegistration::class); }
}
