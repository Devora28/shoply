<?php
namespace App\Services;
use App\Models\Product;
class HomeService{
    public function index(){
        $heroProduct = Product::where('is_active',true)->with(['category'])->inRandomOrder()->first();
        $flashSaleProducts = Product::where('is_active',true)->with(['category','brand'])->where('discount','>=',10)->inRandomOrder()->limit(10)->get();
        $featuredProducts = Product::where('is_active',true)->where('is_featured',true)->with(['category','brand'])->inRandomOrder()->limit(8)->get();
        $bestSellers = Product::where('is_active',true)->where('sold_count','>',1)->with(['category','brand'])->orderBy('sold_count','desc')->limit(10)->get();
        $newArrivals = Product::where('is_active',true)->with(['category','brand'])->latest()->limit(10)->get();
        return [
            'heroProduct' => $heroProduct,
            'flashSaleProducts' => $flashSaleProducts,
            'featuredProducts' => $featuredProducts,
            'bestSellers' => $bestSellers,
            'newArrivals' => $newArrivals,
        ];
    }
}
