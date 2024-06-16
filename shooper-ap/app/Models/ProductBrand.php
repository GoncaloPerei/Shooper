<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBrand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slogan',
    ];

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'brand_products', 'brand_id', 'product_id');
    }
}
