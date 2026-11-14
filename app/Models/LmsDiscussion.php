<?php

namespace App\Models;

class LmsDiscussion extends BaseModel
{
    public function subject() { return $this->belongsTo(Subject::class); }
    public function user() { return $this->belongsTo(User::class); }
}
