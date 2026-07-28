<?php
namespace App\Services;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
class ProductService{
    public function productDetails(Product $product){
        $product->load([
            'brand',
            'category',
            'images'
        ]);
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('stock','>',0)
            ->where('category_id',$product->category_id)
            ->orderByRaw('brand_id = ? DESC',[$product->brand_id])
            ->with(['brand','category'])
            ->take(10)
            ->get();
        return [
            'product' => new ProductResource($product),
            'relatedProducts' => ProductCardResource::collection($relatedProducts),
        ];
    }
}
