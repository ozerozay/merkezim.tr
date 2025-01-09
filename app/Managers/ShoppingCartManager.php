<?php

namespace App\Managers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ShopPackage;
use App\Models\ShopService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ShoppingCartManager
{
    protected $sessionKey = 'cart';

    public function getCart()
    {
        return Auth::check() ? $this->getDatabaseCart() : $this->getSessionCart();
    }

    public function getDatabaseCart()
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function getSessionCart(): Collection
    {
        return collect(session()->get($this->sessionKey, []));
    }

    public function getCartCount()
    {
        if (Auth::check()) {
            return $this->getCart()->items()->count();
        }
        return $this->getSessionCart()->sum('quantity');
    }

    public function addItem(array $product, int $quantity = 1): int
    {
        return Auth::check() ? $this->addToDatabase($product, $quantity) : $this->addToSession($product, $quantity);
    }

    protected function addToDatabase(array $product, int $quantity): int
    {
        $cart = $this->getDatabaseCart();
        $item = $this->getItem($product['type'], $product['id']);

        $existingCartItem = $cart->items()
            ->where('item_id', $item->id)
            ->where('item_type', get_class($item))
            ->first();

        if ($existingCartItem) {
            if ($item->buy_max > 0 && $item->buy_max < $existingCartItem->quantity + $quantity) {
                $existingCartItem->quantity = $item->buy_max;
            } else {
                $existingCartItem->quantity += $quantity;
            }
            $existingCartItem->save();

            return $existingCartItem->quantity;
        }

        $cartItem = new CartItem([
            'item_id' => $item->id,
            'item_type' => get_class($item),
            'quantity' => ($item->buy_max > 0 && $item->buy_max < $quantity) ? $item->buy_max : $quantity,
            'price' => $item->price,
        ]);
        $cart->items()->save($cartItem);

        return $cartItem->quantity;
    }

    protected function addToSession(array $product, int $quantity): int
    {
        $cart = $this->getSessionCart();
        $productId = $product['id'];

        if ($cart->has($productId)) {
            $currentItem = $cart->get($productId);
            $currentItem['quantity'] += $quantity;
            $cart->put($productId, $currentItem);
        } else {
            $cart->put($productId, array_merge($product, ['quantity' => $quantity]));
        }

        session()->put($this->sessionKey, $cart->toArray());

        return $cart->get($productId)['quantity'];
    }

    protected function getItem($type, $id)
    {
        return match ($type) {
            'package' => ShopPackage::findOrFail($id),
            'service' => ShopService::findOrFail($id),
            default => throw new \Exception('Unknown item type'),
        };
    }

    public function syncSessionToDatabase()
    {
        if (!Auth::check()) return;

        $sessionCart = $this->getSessionCart();

        if ($sessionCart->isEmpty()) return;

        foreach ($sessionCart as $item) {
            $this->addToDatabase($item, $item['quantity']);
        }

        session()->forget($this->sessionKey);
    }

    public function getSubTotal()
    {
        if (Auth::check()) {
            return $this->getCart()->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        }

        return $this->getSessionCart()->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function getTotalTax()
    {
        return $this->getSubTotal(); // Vergi hesaplaması gerekiyorsa burada yapılabilir
    }
}
