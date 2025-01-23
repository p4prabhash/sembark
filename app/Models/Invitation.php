<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email', 
        'token',
        'expires_at',
        'is_signed_up',
        'team_id',
    ];

}
