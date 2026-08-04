<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product,string $slug = null)
    {
        return response()->json([
            'success' => true,
            'message' => 'Product Details',
            'data' => $this->productService->productDetails($product)
        ]);
    }
    public function reviews(Request $request,Product $product){
        return response()->json([
            'success' => true,
            'message' => 'Product Reviews',
            'data' => $this->productService->productReviews($request,$product)
        ]);
    }
    public function storeReview(Request $request,Product $product){
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|min:5|max:100',
            'comment' => 'required|string|min:5|max:2000',
            'pros' => 'nullable|string|max:100',
            'cons' => 'nullable|string|max:100',
        ]);
        $review = $this->productService->createReview($product,$validated,auth()->id());
        return response()->json([
            'success' => true,
            'message' => 'Review Added Successfully',
        ],201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
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
