<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'brand_id',
        'name',
        'slug',
        'image',
        'sku',
        'description',
        'quantity',
        'price',
        'is_visible',
        'is_featured',
        'type',
        'publish_at'
    ];

    public function brand(): BelongsTo{
        return $this->belongsTo(Brand::class);
    }

    public function categories() : BelongsToMany{
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
