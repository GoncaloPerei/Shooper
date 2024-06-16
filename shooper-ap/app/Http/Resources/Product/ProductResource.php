<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\ProductUrl\ProductUrlResource;
use App\Http\Resources\PriceHistory\PriceHistoryProductResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $latestPriceHistory = $this->priceHistory
            ->where('product_id', $this->id)
            ->last();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'filename' => $this->filename,
            'category' => $this->productCategory ? $this->productCategory->name : null,
            'priceHistory' => PriceHistoryProductResource::collection($this->priceHistory) ?? null,
            'avgPrice' => $latestPriceHistory ? $latestPriceHistory->avg_price : null,
            'minPrice' => $latestPriceHistory ? $latestPriceHistory->min_price : null,
            'urls' => ProductUrlResource::collection($this->productUrl) ?? null,
            'productUrlCount' => count($this->productUrl),
            'status' => $this->status->name,
            'createdBy' => $this->user->name,
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updatedAt' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
            'deletedAt' => date('Y-m-d H:i:s', strtotime($this->deleted_at)),
        ];
    }
}
