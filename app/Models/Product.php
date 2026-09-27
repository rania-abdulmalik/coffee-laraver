<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * الحقول المسموح إدخال البيانات إليها (Mass Assignment)
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'rating',
        'image',
        'beans',
        'serving',
        'cup_size',
        'origin',
    ];

    /**
     * تحويل أنواع البيانات (Casting) تلقائياً
     * هذا خيار ممتاز لضمان تعامل لارافل مع السعر والتقييم كأرقام وليس كنصوص
     */
    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
    ];

    /**
     * علاقة المنتج بالتصنيف (القهوة تنتمي لتصنيف واحد)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
