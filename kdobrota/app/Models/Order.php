<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'shipping_address',
        'phone',
        'notes'
    ];

    /**
     * Narudžbina pripada jednom korisniku
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Narudžbina može imati više stavki
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Narudžbina može imati više proizvoda kroz stavke narudžbine
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot(['quantity', 'price', 'subtotal']);
    }
}
