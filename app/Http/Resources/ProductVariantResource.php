<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Lấy các trường từ model ProductVariant (this)
            'id' => $this->id,
            'size' => $this->size,
            'color' => $this->color,
            'price' => $this->price,
            'image_url' => $this->image_url,
            'stock_quantity' => $this->stock_quantity,
            'sold_count' => $this->sold_count,

            // Lấy tên sản phẩm từ model Product đã được liên kết
            'product_name' => $this->whenLoaded('product', function () {
                return $this->product->name;
            }),
        ];
    }
}
