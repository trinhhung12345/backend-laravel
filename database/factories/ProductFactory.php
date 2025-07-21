<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Tự động tạo hoặc lấy một category_id
            'category_id' => Category::factory(),
            'name' => 'Sản phẩm ' . fake()->words(3, true),
            'description' => fake()->paragraph(5),
        ];
    }
}
