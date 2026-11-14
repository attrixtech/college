<?php

namespace App\Models;

class Campus extends BaseModel
{
    public function colleges() { return $this->hasMany(College::class); }
}
