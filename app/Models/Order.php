<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'email', 'phone',
        'address', 'city', 'payment_method', 'notes',
        'total', 'status',
    ];

    protected $casts = ['total' => 'decimal:2'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}