<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_name',
        'subtotal',
        'tax',
        'total',
        'payment_method'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
