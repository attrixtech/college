<?php

namespace App\Models;

class InventoryItem extends BaseModel
{
    public function department() { return $this->belongsTo(Department::class); }
}
