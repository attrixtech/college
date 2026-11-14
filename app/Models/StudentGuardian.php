<?php

namespace App\Models;

class StudentGuardian extends BaseModel
{
    public function student() { return $this->belongsTo(Student::class); }
    public function parentUser() { return $this->belongsTo(User::class, "parent_user_id"); }
}
