<?php

namespace App\Http\Resources\ProductCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Product\ProductResource;

class ProductCategoryResource extends JsonResource
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
            'filename' => $this->filename,
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updatedAt' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
            'deletedAt' => date('Y-m-d H:i:s', strtotime($this->deleted_at)),
            'brand' => $this->productBrand,
            'product' => ProductResource::collection($this->product) ?? null,
            'productCount' => count($this->product) ?? null,
        ];
    }
}
