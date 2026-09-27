<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // عرض جميع التصنيفات
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // إضافة تصنيف جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($validatedData);
        return response()->json($category, 201);
    }

    // عرض تفاصيل تصنيف واحد
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'التصنيف غير موجود'], 404);
        }

        return response()->json($category);
    }

    // تعديل بيانات التصنيف
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'التصنيف غير موجود'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category->update($validatedData);
        return response()->json($category);
    }

    // حذف التصنيف
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'التصنيف غير موجود'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'تم حذف التصنيف بنجاح']);
    }
}