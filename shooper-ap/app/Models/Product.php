<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status_id',
        'product_category_id',
        'filename'
    ];

    public function scopePriceRange(Builder $query, $minPrice, $maxPrice): Builder
    {
        return $query->whereHas('priceHistory', function ($query) use ($minPrice, $maxPrice) {
            if (!is_null($minPrice)) {
                $query->where('min_price', '>=', $minPrice);
            }
            if (!is_null($maxPrice)) {
                $query->where('min_price', '<=', $maxPrice);
            }
        });
    }

    public function brand(): BelongsToMany
    {
        return $this->belongsToMany(ProductBrand::class, 'brand_products', 'product_id', 'brand_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function productUrl(): HasMany
    {
        return $this->hasMany(ProductUrl::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function priceHistory(): HasMany
    {
        return $this->hasMany(PriceHistoryProduct::class);
    }

    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(ProductList::class, 'list_products', 'product_id', 'list_id');
    }

    public function featuredProduct(): HasOne
    {
        return $this->hasOne(FeaturedProduct::class);
    }

    public function priceAlert(): HasMany
    {
        return $this->hasMany(PriceAlert::class);
    }
}
