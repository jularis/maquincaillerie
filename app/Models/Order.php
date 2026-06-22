<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'status', 'subtotal', 'tax', 'shipping', 'total',
        'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postal_code',
        'country', 'payment_method', 'payment_status', 'notes', 'items',
    ];

    protected $casts = ['items' => 'array'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
