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
        'country', 'is_company', 'company_name', 'payment_method', 'payment_status', 'notes', 'items',
    ];

    protected $casts = [];

    public function getItemsDecodedAttribute(): array
    {
        return json_decode($this->attributes['items'] ?? '[]', true) ?? [];
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
