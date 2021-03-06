<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BiomatericAttedance extends Authenticatable
{
    use HasFactory , Notifiable;
    protected $guard = 'biomateric_attedance';
    protected $guarded = [];
    protected $hidden = [
        'remember_token'
    ];
}
