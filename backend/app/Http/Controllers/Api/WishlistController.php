<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(private WishlistService $wishlistService){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $wishlist = $this->wishlistService->getWishlist($request->user());
        return response()->json([
            'success' => true,
            'message' => 'Wishlist found',
            'data' => new WishlistResource($wishlist)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$id)
    {
        $result = $this->wishlistService->addWishlistItem($request->user(),$id);
        if($result){
            return response()->json([
                'success' => true,
                'message' => 'Item added to wishlist',
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Item not added to wishlist',
            ]);
        }
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
    public function destroy(Request $request,$id)
    {
        $this->wishlistService->removeWishlistItem($request->user(),$id);
        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist',
        ]);
    }
}
