<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Bọc các route API của bạn trong group này
Route::prefix('api')->group(function () {

    // Các route public (không cần đăng nhập) có thể đặt ở đây
    // ví dụ: Route::get('/products', [ProductController::class, 'index']);

    Route::get('/hello', function () {
        return response()->json([
            'message' => 'Xin chào, kết nối từ Frontend đến Backend đã thành công!'
        ]);
    });

    // Các route cần xác thực
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/cart', [CartController::class, 'addItem']);

        // Thêm các API route cần bảo vệ khác của bạn ở đây
        // ví dụ: Route::get('/user', function (Request $request) {
        //     return $request->user();
        // });
    });


});
