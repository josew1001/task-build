<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    public function userCreated() {
        return $this->belongsTo(User::class, 'user_created_id');
    }
}
