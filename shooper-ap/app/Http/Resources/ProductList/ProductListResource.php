<?php

namespace App\Http\Resources\ProductList;

use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'productsCount' => $this->products_count,
            'products' => ProductResource::collection($this->products),
            'createdBy' => $this->user,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,
        ];
    }
}
