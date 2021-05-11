<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    public function user() {
        return $this->hasOne(\App\Models\User::class,'id','user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        
        return Carbon::parse($value)->diffForHumans();
    }
}
