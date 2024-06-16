<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUrl extends Model
{
    use HasFactory, SoftDeletes;

    public $product;

    protected $fillable = [
        'url',
        'name',
        'price',
        'scratched_price',
        'product_id',
        'store_id',
        'user_id',
        'status_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function priceHistoryUrl(): HasMany
    {
        return $this->hasMany(PriceHistoryUrl::class);
    }
}
