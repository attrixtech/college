<?php

namespace App\Models;

class TransportVehicle extends BaseModel
{
    public function routes() { return $this->hasMany(TransportRoute::class); }
}
