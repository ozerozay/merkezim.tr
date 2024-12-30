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
        return $this->getCart()->items()->count();
    }

    public function addItem(array $product, int $quantity = 1): int
    {
        return Auth::check() ? $this->addToDatabase($product, $quantity) : $this->addToSession($product, $quantity);

    }

    protected function addToDatabase(array $product, int $quantity): int
    {
        $cart = $this->getDatabaseCart();

        // Ürün veya hizmeti alıyoruz
        $item = $this->getItem($product['type'], $product['id']);

        // Sepette zaten var mı kontrolü
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
            if ($existingCartItem->quantity < 1) {
                $existingCartItem->delete();

                return 0;
            }

            return $existingCartItem->quantity;
        } else {
            $cartItem = new CartItem([
                'item_id' => $item->id,
                'item_type' => get_class($item),
                'quantity' => ($item->buy_max > 0 && $item->buy_max < $quantity) ? $item->buy_max : $quantity,
                'price' => $item->price,
            ]);
            $cart->items()->save($cartItem);

            return $cartItem->quantity;
        }
    }

    protected function addToSession(array $product, int $quantity)
    {
        $cart = $this->getSessionCart();
        $productId = $product['id'];

        // Sepette zaten var mı kontrolü

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = array_merge($product, ['quantity' => $quantity]);
        }

        session()->put($this->sessionKey, $cart);

        return $cart[$productId]['quantity'];
    }

    // Ürün veya hizmeti alıyoruz
    protected function getItem($type, $id)
    {
        if ($type === 'package') {
            return ShopPackage::findOrFail($id);
        } elseif ($type === 'service') {
            return ShopService::findOrFail($id);
        }

        throw new \Exception('Unknown item type');
    }

    public function syncSessionToDatabase()
    {
        $cart = session()->get($this->sessionKey, []);

        if (empty($cart)) {
            return;
        }

        foreach ($cart as $productId => $item) {
            $this->addToDatabase($item, $item['quantity']);
        }

        session()->forget($this->sessionKey); // Oturumdaki sepeti temizle
    }

    public function getSubTotal()
    {
        $cart = collect($this->getCart()->items);

        //dump($cart);

        return $cart->sum(function ($v) {
            return $v['price'] * $v['quantity'];
        });
    }

    public function getTotalTax()
    {
        $cart = collect($this->getCart()->items);

        //dump($cart);

        return $cart->sum(function ($v) {
            return $v['price'] * $v['quantity'];
        });
    }
}
