<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\CheckoutService;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class CheckoutController extends Controller
{
    public function __construct(private CheckoutService $checkoutService,private CouponService $couponService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkoutService->getAddresses($request->user());
        return response()->json([
            'success' => true,
            'message' => 'Addresses retrieved',
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
    public function validatePromoCode(Request $request) {
        $user = $request->user();
        $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
            ],
        ]);
        $cart = $user->cart()->firstOrCreate();
        $coupon = $this->couponService->validate($user,$request->code,$cart);
        $discount = $this->couponService->calculateDiscount($cart,$coupon);
        Redis::setex(
            "user:{$user->id}:coupon",
            86400,
            $coupon->id
        );
        return response()->json([
            'success' => true,
            'message' => 'Promo code valid',
            'data' => [
                'code' => $coupon->code,
                'discount' => $discount,
            ]
        ]);
    }
    public function refreshPromoCode(Request $request)
    {
        $user = $request->user();
        $couponId = Redis::get("user:{$user->id}:coupon");
        if (!$couponId) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }
        $cart = $user->cart()->firstOrCreate();
        $coupon = Coupon::find($couponId);
        if (!$coupon) {
            Redis::del("user:{$user->id}:coupon");
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }
        try {
            $this->couponService->validate(
                $user,
                $coupon->code,
                $cart
            );
            $discount = $this->couponService->calculateDiscount(
                $cart,
                $coupon
            );
            return response()->json([
                'success' => true,
                'data' => [
                    'code' => $coupon->code,
                    'discount' => $discount,
                ],
            ]);
        } catch (\Throwable $e) {
            Redis::del("user:{$user->id}:coupon");
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }
    }
    public function removePromoCode(Request $request)
    {
        $user = $request->user();
        Redis::del("user:{$user->id}:coupon");
        return response()->json([
            'success' => true,
            'message' => 'Promo code removed',
        ]);
    }
}
