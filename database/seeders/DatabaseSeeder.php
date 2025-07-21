<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo một tài khoản Admin và một tài khoản Customer mẫu
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'), // Mật khẩu là "password"
        ]);

        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'role' => 'customer',
            'password' => Hash::make('password'),
        ]);

        // 2. Sử dụng Factory để tạo dữ liệu phức tạp
        // Tạo 6 danh mục
        Category::factory(6)
            // Mỗi danh mục sẽ "có" 10 sản phẩm
            ->has(Product::factory()->count(10)
                // Mỗi sản phẩm lại "có" 4 biến thể (size/màu)
                ->has(ProductVariant::factory()->count(4), 'variants') // 'variants' là tên của relationship trong model Product
            )
            ->create();

        $this->command->info('Database seeded successfully!');
    }
}
