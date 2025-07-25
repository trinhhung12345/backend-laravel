<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- THÊM
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory; // <--- THÊM

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
