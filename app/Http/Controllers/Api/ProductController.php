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
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // http_build_query sẽ tự động tạo key cache duy nhất cho mọi tổ hợp filter
        $cacheKey = 'products.filtered.' . http_build_query($request->all());

        $products = Cache::remember($cacheKey, 60, function () use ($request) {
            $query = Product::with('variants');

            // --- LỌC THEO GIÁ (LOGIC MỚI) ---
            // Chỉ lọc khi có cả min và max
            if ($request->filled('min_price') && $request->filled('max_price')) {
                $query->whereHas('variants', function ($q) use ($request) {
                    $q->whereBetween('price', [$request->min_price, $request->max_price]);
                });
            }

            // --- LỌC THEO CATEGORY (LOGIC CŨ) ---
            if ($request->has('categories')) {
                $categorySlugs = explode(',', $request->input('categories'));
                $query->whereHas('category', function ($q) use ($categorySlugs) {
                    $q->whereIn('slug', $categorySlugs);
                });
            }

            // --- LỌC THEO TÌM KIẾM (LOGIC CŨ) ---
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where('name', 'like', "%{$searchTerm}%");
            }

            // --- SẮP XẾP (ĐƯỢC CẢI TIẾN) ---
            $sort = $request->input('sort', 'latest');
            if ($sort === 'price_asc' || $sort === 'price_desc') {
                // Để sắp xếp theo giá của biến thể, chúng ta cần join
                $direction = ($sort === 'price_asc') ? 'asc' : 'desc';
                $query->select('products.*')
                    ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->groupBy('products.id')
                    ->orderBy(DB::raw('MIN(product_variants.price)'), $direction);
            } else {
                $query->latest(); // Sắp xếp theo created_at desc
            }

            return $query->paginate($request->input('per_page', 12));
        });

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
