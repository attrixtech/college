<?php

namespace App\Models;

class PurchaseOrder extends BaseModel
{
    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function requisition() { return $this->belongsTo(PurchaseRequisition::class, "purchase_requisition_id"); }
}
