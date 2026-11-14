<?php

namespace App\Models;

class AuditLog extends BaseModel
{
    public function user() { return $this->belongsTo(User::class); }
}
