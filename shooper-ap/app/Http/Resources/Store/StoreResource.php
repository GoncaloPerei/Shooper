<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'websiteUrl' => $this->website_url,
            'productTitleTag' => $this->title_tag,
            'productPriceTag' => $this->price_tag,
            'productScratchedTag' => $this->scratched_price_tag,
            'productDeliveryEstimateTag' => $this->delivery_estimate_tag,
            'status' => $this->status->name ?? null,
            'filename' => $this->filename,
            'deletedAt' => date('Y-m-d H:i:s', strtotime($this->deleted_at)),
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updatedAt' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
        ];
    }
}
