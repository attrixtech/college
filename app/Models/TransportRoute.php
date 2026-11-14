<?php

namespace App\Models;

class TransportRoute extends BaseModel
{
    protected $casts = ["stops_json" => "array"];
    public function vehicle() { return $this->belongsTo(TransportVehicle::class, "transport_vehicle_id"); }
    public function allocations() { return $this->hasMany(TransportAllocation::class); }
}
