<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            // Lấy category name
            'category' => $this->whenLoaded('category', function() {
                return $this->category->name;
            }),
            // Lấy tất cả các biến thể, sử dụng lại ProductVariantResource
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
        ];
    }
}
