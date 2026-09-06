<?php
namespace App\Services;
use App\Http\Resources\ProductReviewResource;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductReviewService{
    public function productReviews(Request $request,Product $product)
    {
        $query = $product->reviews()
            ->with(['user','pros','cons','votes']);
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
        $isVerifiedPurchase = OrderItem::query()
            ->where('product_id', $product->id)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('status', 'delivered');
            })
            ->exists();
        $review = $product->reviews()->create([
            'user_id' => $userId,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'comment' => $data['comment'],
            'is_verified_purchase' => $isVerifiedPurchase,
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
    public function handleVote(ProductReview $review,User $user,bool $isHelpful){
        return DB::transaction(function () use ($review, $user, $isHelpful) {
            $vote = $review->votes()
                ->where('user_id', $user->id)
                ->first();
            if (!$vote) {
                $review->increment(
                    $isHelpful ? 'helpful_count' : 'not_helpful_count'
                );
                return $review->votes()->create([
                    'user_id' => $user->id,
                    'is_helpful' => $isHelpful,
                ]);
            }
            if ($vote->is_helpful === $isHelpful) {
                $review->decrement(
                    $isHelpful ? 'helpful_count' : 'not_helpful_count'
                );
                $vote->delete();
                return null;
            }
            $review->decrement(
                $vote->is_helpful ? 'helpful_count' : 'not_helpful_count'
            );
            $review->increment(
                $isHelpful ? 'helpful_count' : 'not_helpful_count'
            );
            $vote->update([
                'is_helpful' => $isHelpful,
            ]);
            return $vote;
        });
    }
}
