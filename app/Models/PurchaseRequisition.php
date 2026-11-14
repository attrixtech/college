<?php

namespace App\Models;

class PurchaseRequisition extends BaseModel
{
    public function department() { return $this->belongsTo(Department::class); }
    public function requester() { return $this->belongsTo(User::class, "requested_by"); }
}
