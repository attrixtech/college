<?php

namespace App\Models;

class Vendor extends BaseModel
{
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
}
