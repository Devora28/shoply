<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\HomeService;

class HomeController extends Controller
{
    public function index(HomeService $homeService){
        $data = $homeService->index();
        return response()->json([
            'heroProduct' => $data['heroProduct'],
            'flashSaleProducts' => $data['flashSaleProducts'],
            'featuredProducts' => $data['featuredProducts'],
            'bestSellers' => $data['bestSellers'],
            'newArrivals' => $data['newArrivals'],
        ]);
    }
}
