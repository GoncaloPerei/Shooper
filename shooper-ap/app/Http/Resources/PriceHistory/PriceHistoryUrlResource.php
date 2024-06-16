<?php

namespace App\Http\Resources\PriceHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceHistoryUrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'normalPrice' => $this->normal_price,
            'scratchedPrice' => $this->scratched_price,
        ];
    }
}
