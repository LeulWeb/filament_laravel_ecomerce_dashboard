<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'number',
        'total_price',
        'status',
        'shipping_price',
        'notes'
    ];

    // Order and customer are related
    public function customer(): BelongsTo{
        return $this->belongsTo(Customer::class);
    }

    // a given order can have many items with a price
    public function items() : HasMany{
        return   $this->hasMany(OrderItem::class);
    }


}
