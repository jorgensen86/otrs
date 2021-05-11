<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    

    public function user()
    {
      return $this->belongsTo(\App\Models\User::class);
    }

    public function replies()
    {
      return $this->hasMany(\App\Models\Reply::class, 'ticket_id');
    }

    public function ticketStatus()
    {
      return $this->hasOne(\App\Models\TicketStatus::class, 'id', 'ticket_status_id');
    }

    public function getCreatedAtAttribute($value)
    {
        
        return Carbon::parse($value)->diffForHumans();
    }
}
