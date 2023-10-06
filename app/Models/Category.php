<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable= [
        'name',
        'slug',
        'parent_id',
        'is_visible',
        'description',
    ];

    // belongs to 
    public function parent(): BelongsTo{
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // has many child
    public function child(): HasMany{
        return $this->hasMany(Category::class, 'parent_id');
    }

    // has many products
    public function products() : HasMany{
        return $this->hasMany(Product::class);
    }
}
