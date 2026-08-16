<?php
namespace App\Services;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductReviewResource;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductService{
    public function productDetails(Product $product){
        $product->load([
            'brand',
            'category',
            'images',
        ])->loadCount('reviews');
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
    public function productReviews(Request $request,Product $product)
    {
        $query = $product->reviews()
            ->with(['user','pros','cons']);
        switch($request->query('review_sort')){
            case 'helpful': $query->orderBy('helpful_count','DESC');
            break;
            case 'oldest': $query->orderBy('created_at','ASC');
            break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'DESC');
                break;
        }
        switch($request->query('review_filter')){
            case '5': $query->where('rating','5');
            break;
            case '4': $query->where('rating','4');
            break;
            case '3': $query->where('rating','3');
            break;
            case 'verified': $query->where('is_verified_purchase',true);
            break;
        }
        $reviews = $query->cursorPaginate(5);
        $stats = $product->reviews()
            ->selectRaw('
            COUNT(*) as total,
            ROUND(AVG(rating), 1) as average,
            SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as star5,
            SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as star4,
            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as star3,
            SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as star2,
            SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as star1
        ')
            ->first();
        return [
            'reviews' => [
                'data' => ProductReviewResource::collection($reviews->items()),
                'next_cursor' => $reviews->nextCursor()?->encode(),
                'prev_cursor' => $reviews->previousCursor()?->encode(),
                'per_page' => $reviews->perPage(),
            ],
            'summary' => [
                'average' => (float) $stats->average,
                'total' => (int) $stats->total,
                'breakdown' => [
                    5 => (int) $stats->star5,
                    4 => (int) $stats->star4,
                    3 => (int) $stats->star3,
                    2 => (int) $stats->star2,
                    1 => (int) $stats->star1,
                ],
                'satisfaction' => $stats->total
                    ? round((($stats->star5 + $stats->star4) / $stats->total) * 100)
                    : 0,
            ],
        ];
    }
    public function createReview(Product $product,$data,$userId){
        $review = $product->reviews()->create([
            'user_id' => $userId,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'comment' => $data['comment'],
        ]);
        if(!empty($data['pros'])){
            $review->pros()->create([
                'text' => $data['pros']
            ]);
        }
        if(!empty($data['cons'])){
            $review->cons()->create([
                'text' => $data['cons']
            ]);
        }
        return $review;
    }
}
