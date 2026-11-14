<?php

namespace App\Models;

class PlacementCompany extends BaseModel
{
    public function drives() { return $this->hasMany(PlacementDrive::class); }
}
