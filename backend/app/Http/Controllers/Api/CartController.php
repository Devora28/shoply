<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;
class CartController extends Controller
{
    public function __construct(private CartService $cartService){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cart = $this->cartService->getCart($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Shopping cart retrieved successfully',
            'data' => new CartResource($cart),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->cartService->addItem(
            $request->user(),
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'data' => new CartResource($cart),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->cartService->updateItem(
            $request->user(),
            $id,
            $validated['quantity']
        );

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully',
            'data' => new CartResource($cart),
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $cart = $this->cartService->removeItem(
            $request->user(),
            $id
        );

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully',
            'data' => new CartResource($cart),
        ]);
    }

    public function clear(Request $request)
    {
        $cart = $this->cartService->clearCart(
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Shopping cart cleared successfully',
            'data' => new CartResource($cart),
        ]);
    }

    public function merge(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],
            'items.*.variant_id' => [
                'nullable',
                'exists:product_variants,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cart = $this->cartService->mergeGuestCart(
            $request->user(),
            $validated['items']
        );

        return response()->json([
            'success' => true,
            'message' => 'Guest cart merged successfully',
            'data' => new CartResource($cart),
        ]);
    }
}
