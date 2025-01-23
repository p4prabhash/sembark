<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;
    protected $fillable = [
        'long_url', 
        'short_url',
        'hits',
        'created_by',
        'client_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
