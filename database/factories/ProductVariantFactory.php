<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'color' => fake()->randomElement(['Trắng', 'Đen', 'Xám', 'Xanh Navy', 'Đỏ']),
            'price' => fake()->numberBetween(250, 800) * 1000, // Giá từ 250k đến 800k
            'stock_quantity' => fake()->numberBetween(0, 100), // Tồn kho
            'image_url' => fake()->imageUrl(640, 480, 'clothes', true), // Tạo ảnh placeholder
        ];
    }
}
