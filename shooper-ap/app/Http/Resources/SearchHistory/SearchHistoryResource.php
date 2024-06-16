<?php

namespace App\Http\Resources\SearchHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchHistoryResource extends JsonResource
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
            'searchTerm' => $this->search_term,
        ];
    }
}
