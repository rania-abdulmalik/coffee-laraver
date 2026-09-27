<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // عرض جميع أنواع القهوة مع اسم التصنيف الخاص بها
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    // إضافة قهوة جديدة
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'beans'       => 'nullable|string|max:255',
            'serving'     => 'required|string|max:255',
            'cup_size'    => 'required|string|max:255',
            'origin'      => 'nullable|string|max:255',
        ]);

        // معالجة رفع الصورة إذا تم إرفاقها
        if ($request->hasFile('image')) {
            
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $imagePath;
        }

        $product = Product::create($validatedData);
        return response()->json($product, 201);
    }

    // عرض تفاصيل قهوة واحدة
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        
        if (!$product) {
            return response()->json(['message' => 'المنتج غير موجود'], 404);
        }

        
        return response()->json($product);
    }

    // تحديث بيانات القهوة
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'المنتج غير موجود'], 404);
        }

        $validatedData = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'beans'       => 'nullable|string|max:255',
            'serving'     => 'sometimes|string|max:255',
            'cup_size'    => 'sometimes|string|max:255',
            'origin'      => 'nullable|string|max:255',
        ]);

        // إذا تم رفع صورة جديدة، نقوم بحذف القديمة وحفظ الجديدة
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $imagePath;
        }

        $product->update($validatedData);
        return response()->json($product);
    }

    // حذف القهوة
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'المنتج غير موجود'], 404);
        }

        // حذف الصورة من السيرفر عند حذف المنتج
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return response()->json(['message' => 'تم حذف المنتج بنجاح']);
    }


    // دالة البحث والفلترة الجديدة
    public function search(Request $request)
    {
        // نبدأ الاستعلام مع جلب بيانات التصنيف
        $query = Product::with('category');

        // 1. البحث النصي (في اسم القهوة أو وصفها)
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // تنفيذ الاستعلام وإرجاع النتائج
        $products = $query->get();

        return response()->json($products);
    }
}