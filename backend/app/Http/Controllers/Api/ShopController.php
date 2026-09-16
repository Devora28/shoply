<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ShopService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(private ShopService $shopService){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'popular');
        $filters = [
            'categories' => $request->input('categories', []),
            'brands' => $request->input('brands', []),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'min_rating' => $request->input('min_rating'),
            'in_stock' => $request->boolean('in_stock'),
            'discount' => $request->boolean('discount'),
        ];
        $shopData = $this->shopService->getShopData($sort,$filters);
        $products = $shopData['products'];
        return response()->json([
            'success' => true,
            'message' => 'Shop data retrieved successfully',
            'data' => [
                'products' => [
                    'data' => ProductResource::collection($products),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'categories' => $shopData['categories'],
                'brands' => $shopData['brands'],
                'min_price' => $shopData['min_price'],
                'max_price' => $shopData['max_price'],
            ]
        ]);
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
