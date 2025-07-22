<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant; // Import model ProductVariant
use App\Http\Resources\ProductVariantResource; // Import Resource đã tạo
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('per_page', 12);
        $sort = $request->input('sort', 'latest');

        $cacheKey = "products.card.page.{$page}.limit.{$limit}.sort.{$sort}";

        $products = Cache::remember($cacheKey, 60, function () use ($limit, $sort) {
            // <<--- THAY ĐỔI: TRUY VẤN MODEL PRODUCT ---
            // Query model Product, không phải ProductVariant
            $query = Product::with('variants'); // Eager load 'variants' để resource có thể dùng

            // Xử lý logic sắp xếp (chỉ ví dụ cho 'latest')
            // Sắp xếp theo giá cần kỹ thuật join phức tạp hơn, sẽ làm sau
            switch ($sort) {
                // Bạn có thể thêm logic sắp xếp giá ở đây sau
                // case 'price_asc':
                // case 'price_desc':
                case 'latest':
                default:
                    $query->latest(); // Sắp xếp theo ngày tạo sản phẩm
                    break;
            }

            return $query->paginate($limit);
        });

        // <<--- THAY ĐỔI: SỬ DỤNG RESOURCE MỚI ---
        return ProductCardResource::collection($products);
    }

    /**
     * Hiển thị thông tin một sản phẩm duy nhất.
     */
    public function show(Product $product) // Sử dụng Route Model Binding của Laravel
    {
        // Eager load các quan hệ để tối ưu
        $product->load(['category', 'variants']);

        return new ProductResource($product);
    }

}
