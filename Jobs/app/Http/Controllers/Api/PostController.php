<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Posts;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // جلب الفئات المختارة من الطلب
        $selectedCategories = $request->input('category');

        // بناء الاستعلام لجلب المنشورات
        $query = Posts::with('category');

        // تطبيق الفلترة بناءً على الفئات المختارة
        if ($selectedCategories) {
            $query->whereIn('category_id', $selectedCategories);
        }

        // إرجاع النتائج في شكل JSON
        $posts = $query->get();

        return response()->json($posts);
    }
    
}
