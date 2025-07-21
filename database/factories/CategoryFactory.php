<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        // Chọn một tên ngẫu nhiên từ danh sách cho trước
        $name = fake()->unique()->randomElement([
            'Áo Thun',
            'Áo Sơ Mi',
            'Áo Khoác',
            'Quần Jeans',
            'Quần Tây',
            'Váy & Đầm'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name), // Tự động tạo slug từ tên
        ];
    }
}
