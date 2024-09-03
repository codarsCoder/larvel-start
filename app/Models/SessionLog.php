<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionLog extends Model
{
    use HasFactory;
    // protected $table = 'sessions';
    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
    ];
}
