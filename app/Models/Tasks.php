<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description', 
        'status', 
        'building_id', 
        'user_created_id', 
        'user_updated_id'
    ];
      
    public function userCreated() {
        return $this->belongsTo(User::class, 'user_created_id');
    }

    public function userUpdated() {
        return $this->belongsTo(User::class, 'user_updated_id');
    }

    public function building() {
        return $this->belongsTo(Buildings::class, 'building_id');
    }
    
}
