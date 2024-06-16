<?php

namespace App\Http\Resources\ProductUrl;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\PriceHistory\PriceHistoryUrlResource;
use App\Http\Resources\Store\StoreResource;

class ProductUrlResource extends JsonResource
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
            'url' => $this->url,
            'name' => $this->name,
            'price' => $this->price,
            'scratchedPrice' => $this->scratched_price,
            'deliveryEstimate' => $this->delivery_estimate,
            'historyPrice' => PriceHistoryUrlResource::collection($this->priceHistoryUrl) ?? null,
            'product' => $this->product_id,
            'store' => new StoreResource($this->store) ?? null,
            'createdBy' => $this->user->name ?? null,
            'status' => $this->status->name ?? null,
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updatedAt' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
            'deletedAt' => date('Y-m-d H:i:s', strtotime($this->deleted_at)),
        ];
    }
}
