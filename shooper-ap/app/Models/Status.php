<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'desc',
    ];

    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function producturl(): HasMany
    {
        return $this->hasMany(ProductUrl::class);
    }

    public function store(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
