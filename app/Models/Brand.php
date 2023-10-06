<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'primary_hex',
        'is_visible',
        'description'
    ];

    // relation
    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }
}
