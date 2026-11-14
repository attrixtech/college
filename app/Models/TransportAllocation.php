<?php

namespace App\Models;

class TransportAllocation extends BaseModel
{
    public function route() { return $this->belongsTo(TransportRoute::class, "transport_route_id"); }
    public function student() { return $this->belongsTo(Student::class); }
}
