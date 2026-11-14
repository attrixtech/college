<?php

namespace App\Models;

class FixedAsset extends BaseModel
{
    public function department() { return $this->belongsTo(Department::class); }
}
