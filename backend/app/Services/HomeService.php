<?php
namespace App\Services;
use App\Http\Resources\ProductCardResource;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
class HomeService{
    public function index(){
        $heroProduct = Cache::tags(['homeProducts'])
            ->remember('heroProduct', 21600, function(){
               return Product::where('is_active',true)->with(['category'])->inRandomOrder()->first();
            });
        $flashSaleProducts = Cache::tags(['homeProducts'])
            ->remember('flashSaleProducts', 21600, function(){
                return Product::where('is_active',true)->with(['category','brand'])->where('discount','>=',10)->inRandomOrder()->limit(10)->get();
            });
        $featuredProducts = Cache::tags(['homeProducts'])
            ->remember('featuredProducts', 21600, function(){
                return Product::where('is_active',true)->where('is_featured',true)->with(['category','brand'])->inRandomOrder()->limit(8)->get();
            });
        $bestSellers = Cache::tags(['homeProducts'])
            ->remember('bestSellers', 21600, function(){
                return Product::where('is_active',true)->where('sold_count','>',1)->with(['category','brand'])->orderBy('sold_count','desc')->limit(10)->get();
            });
        $newArrivals = Cache::tags(['homeProducts'])
            ->remember('newArrivals', 21600, function(){
                return Product::where('is_active',true)->with(['category','brand'])->latest()->limit(10)->get();
            });
        return [
            'heroProduct' => new ProductCardResource($heroProduct),
            'flashSaleProducts' => ProductCardResource::collection($flashSaleProducts),
            'featuredProducts' => ProductCardResource::collection($featuredProducts),
            'bestSellers' => ProductCardResource::collection($bestSellers),
            'newArrivals' => ProductCardResource::collection($newArrivals),
        ];
    }
}
