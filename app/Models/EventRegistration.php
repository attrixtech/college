<?php

namespace App\Models;

class EventRegistration extends BaseModel
{
    public function event() { return $this->belongsTo(CampusEvent::class, "campus_event_id"); }
    public function student() { return $this->belongsTo(Student::class); }
}
