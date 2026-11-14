<?php

namespace App\Models;

class AcademicYear extends BaseModel
{
    public function sessions() { return $this->hasMany(AcademicSession::class); }
}
