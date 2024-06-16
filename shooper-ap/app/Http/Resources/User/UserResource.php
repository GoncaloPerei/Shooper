<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\ProductList\ProductListResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => new RoleResource($this->role),
            'productLists' => ProductListResource::collection($this->productList),
            'productListCount' => $this->product_list_count,
            'searchHistory' => $this->searchHistory,
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updatedAt' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
            'deletedAt' => date('Y-m-d H:i:s', strtotime($this->deleted_at)),
        ];
    }
}
