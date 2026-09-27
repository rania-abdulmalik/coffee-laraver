<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * علاقة التصنيف بالمنتجات (التصنيف الواحد يحتوي على عدة منتجات)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
