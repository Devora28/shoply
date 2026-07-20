<?php
namespace App\Services;
use App\Models\Category;
class CategoryService{
    public function getCategories(){
        return $categories = Category::where('is_active',true)->whereNull('parent_id')->with('children')->get();
    }
}
