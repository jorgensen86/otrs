<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'role_id',
        'is_admin',
        'active',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function getStatusAttribute($value) 
    // {
    //     return $value ? __('app.text_yes') : __('app.text_no');
    // }

    // public function getisAdminAttribute($value) 
    // {
    //     return $value ? __('app.text_yes') : __('app.text_no');
    // }

    public function customerInfo() {
        return $this->hasOne(CustomerInfo::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }
    
    public function permissions() {
        return $this->belongsToMany(Permission::class);
    }
}
