<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Lấy biến thể đầu tiên làm đại diện
        $representativeVariant = $this->whenLoaded('variants', function() {
            return $this->variants->first();
        });

        return [
            'id' => $this->id, // ID của sản phẩm gốc
            'product_name' => $this->name, // Tên của sản phẩm gốc

            // Lấy thông tin từ biến thể đại diện
            'price' => $representativeVariant ? $representativeVariant->price : '0.00',
            'image_url' => $representativeVariant ? $representativeVariant->image_url : 'https://placehold.co/600x800.png?text=No+Image',
        ];
    }
}
