<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
class CategoryController extends Controller
{
    public function index(CategoryService $categoryService){
        return response()->json([
            'categories' => $categoryService->getCategories(),
        ]);
    }
}
