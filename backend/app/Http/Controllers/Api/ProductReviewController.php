<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
use App\Models\Product;
use App\Models\ProductReview;
use App\Services\ProductReviewService;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function __construct(private ProductReviewService  $productRevieService){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,Product $product){
        return response()->json([
            'success' => true,
            'message' => 'Product Reviews',
            'data' => $this->productRevieService->productReviews($request,$product)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|min:5|max:100',
            'comment' => 'required|string|min:5|max:2000',
            'pros' => 'nullable|string|max:100',
            'cons' => 'nullable|string|max:100',
        ]);
        $this->productRevieService->createReview($product,$validated,auth()->id());
        return response()->json([
            'success' => true,
            'message' => 'Review Added Successfully',
        ],201);
    }
    public function reviewVote(Request $request,ProductReview $review){
        $validated = $request->validate([
            'is_helpful' => 'required|boolean',
        ]);
        $this->productRevieService->handleVote($review,$request->user(),$validated['is_helpful']);
        $review->refresh();
        $review->load([
            'user',
            'pros',
            'cons',
            'votes' => function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            }
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Vote actions recorded',
            'data' => new ProductReviewResource($review)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
