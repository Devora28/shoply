<?php
namespace App\Services;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
class ShopService{
    public function getShopData($sorts,$filters){
        $query = Product::query()->with(['brand']);
        match ($sorts) {
            'newest' => $query->latest(),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating' => $query->orderByDesc('rating'),
            'popular' => $query->orderByDesc('sold_count'),
        };
        $query->when($filters['categories'] ?? null, function ($query, $categories) {
            $query->whereHas('category', function ($query) use ($categories) {
                $query->whereIn('slug', $categories);
            });
        });
        $query->when($filters['brands'] ?? null, function ($query, $brands) {
            $query->whereHas('brand', function ($query) use ($brands) {
                $query->whereIn('slug', $brands);
            });
        });
        $query->when($filters['min_price'] ?? null, function ($query, $minPrice) {
            $query->where('price', '>=', $minPrice);
        });
        $query->when($filters['max_price'] ?? null, function ($query, $maxPrice) {
            $query->where('price', '<=', $maxPrice);
        });
        $query->when($filters['min_rating'] ?? null, function ($query, $rating) {
            $query->where('rating', '>=', $rating);
        });
        $query->when($filters['in_stock'] ?? false, function ($query) {
            $query->where('stock', '>', 0);
        });
        $query->when($filters['discount'] ?? false, function ($query) {
            $query->where('discount', '>', 0);
        });
        $minPrice = (clone $query)->min('price');
        $maxPrice = (clone $query)->max('price');
        return [
            'products' => $query->paginate(16),
            'categories' => $this->getCategories(),
            'brands' => $this->getBrands(),
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
        ];
    }
    private function getCategories(){
        return Category::query()
            ->whereHas('products')
            ->get();
    }
    private function getBrands(){
        return Brand::query()
            ->whereHas('products')
            ->get();
    }
}
