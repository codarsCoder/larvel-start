<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'transaction_id',
        'payment_method',
        'payment_type',
        'amount',
        'currency',
        'current_amount',
        'current_currency',
        'status',
        'description',
    ];
}
