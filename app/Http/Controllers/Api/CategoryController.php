<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        // Lấy tất cả categories, cache lại trong 1 giờ
        $categories = \Illuminate\Support\Facades\Cache::remember('all_categories', 3600, function () {
            return Category::all();
        });
        return CategoryResource::collection($categories);
    }
}
