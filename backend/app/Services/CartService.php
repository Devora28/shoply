<?php
namespace App\Services;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
class CartService{
    public function getCart(User $user){
        $cart = $user->cart()->firstOrCreate();
        $cart->load(['items.product','items.variant']);
        return $cart;
    }
    public function addItem(User $user, array $data): Cart{
        return DB::transaction(function () use ($user, $data) {
            $cart = $user->cart()->firstOrCreate();
            $item = $cart->items()
                ->where('product_id', $data['product_id'])
                ->where(function ($query) use ($data) {
                    if ($data['variant_id'] === null) {
                        $query->whereNull('variant_id');
                    }
                    else{
                        $query->where('variant_id', $data['variant_id']);
                    }
                })->first();
            if ($item) {
                $item->increment('quantity', $data['quantity']);
            }
            else {
                $cart->items()->create([
                    'product_id' => $data['product_id'],
                    'variant_id' => $data['variant_id'],
                    'quantity' => $data['quantity'],
                ]);
            }
            return $this->getCart($user);
        });
    }
    public function updateItem( User $user, string $itemId, int $quantity ): Cart {
        $cart = $user->cart()->firstOrCreate();
        $item = $cart->items()->find($itemId);
        if (!$item) {
            throw new ModelNotFoundException();
        }
        $item->update([ 'quantity' => $quantity, ]);
        return $this->getCart($user);
    }
    public function removeItem( User $user, string $itemId ): Cart {
        $cart = $user->cart()->firstOrCreate();
        $item = $cart->items()->find($itemId);
        if (!$item) {
            throw new ModelNotFoundException();
        } $item->delete();
        return $this->getCart($user);
    }
    public function clearCart(User $user): Cart {
        $cart = $user->cart()->firstOrCreate();
        $cart->items()->delete();
        return $this->getCart($user);
    }
    public function mergeGuestCart(User $user, array $items): Cart
    {
        return DB::transaction(function () use ($user, $items) {
            foreach ($items as $item) {
                $cart = $user->cart()->firstOrCreate();
                $cartItem = $cart->items()
                    ->where('product_id', $item['product_id'])
                    ->where(function ($query) use ($item) {
                        if (($item['variant_id'] ?? null) === null) {
                            $query->whereNull('variant_id');
                        } else {
                            $query->where('variant_id', $item['variant_id']);
                        }
                    })
                    ->first();
                if ($cartItem) {
                    $cartItem->increment(
                        'quantity',
                        $item['quantity']
                    );
                } else {
                    $cart->items()->create([
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'] ?? null,
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
            return $this->getCart($user);
        });
    }
}
