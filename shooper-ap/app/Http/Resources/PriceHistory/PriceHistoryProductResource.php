<?php

namespace App\Http\Resources\PriceHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceHistoryProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'avgPrice' => $this->avg_price,
            'minPrice' => $this->min_price,
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updatedAt' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
        ];
    }
}
