<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'desc',
        'filename',
    ];

    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productUrl(): HasManyThrough
    {
        return $this->hasManyThrough(ProductUrl::class, Product::class);
    }
}
