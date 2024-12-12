<?php

namespace App\Managers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ShopPackage;
use App\Models\ShopService;
use Illuminate\Support\Facades\Auth;

class ShoppingCartManager
{
    protected $sessionKey = 'cart';

    public function getCart()
    {
        if (Auth::check()) {
            return $this->getUserCart();
        }

        return collect(session()->get($this->sessionKey, []));
    }

    public function getCartCount()
    {
        return Auth::check() ? $this->getUserCartCount() : count(value: session()->get($this->sessionKey, []));
    }

    public function addItem(array $product, int $quantity = 1)
    {
        if (Auth::check()) {
            $this->addToDatabase($product, $quantity);
        } else {
            $this->addToSession($product, $quantity);
        }
    }

    protected function getUserCartCount()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        return $cart->items()->count();
    }

    protected function getUserCart()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        return $cart->items()->with('item')->get();
    }

    protected function addToDatabase(array $product, int $quantity)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Ürün veya hizmeti alıyoruz
        $item = $this->getItem($product['type'], $product['id']);

        // Sepette zaten var mı kontrolü
        $existingCartItem = $cart->items()
            ->where('item_id', $item->id)
            ->where('item_type', get_class($item))
            ->first();

        if ($existingCartItem) {
            $existingCartItem->quantity += $quantity;
            $existingCartItem->save();
        } else {
            // Eğer ürün sepette yoksa, yeni bir öğe ekle
            $cartItem = new CartItem([
                'item_id' => $item->id,
                'item_type' => get_class($item),
                'quantity' => $quantity,
                'price' => $item->price,
            ]);
            $cart->items()->save($cartItem);
        }
    }

    protected function addToSession(array $product, int $quantity)
    {
        $cart = session()->get($this->sessionKey, []);
        $productId = $product['id'];

        // Sepette zaten var mı kontrolü
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = array_merge($product, ['quantity' => $quantity]);
        }

        session()->put($this->sessionKey, $cart);
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
        $cart = collect($this->getCart());

        //dump($cart);

        return $cart->sum(function ($v) {
            return $v['price'] * $v['quantity'];
        });
    }

    public function getTotalTax()
    {
        $cart = collect($this->getCart());

        //dump($cart);

        return $cart->sum(function ($v) {
            return $v['price'] * $v['quantity'];
        });
    }
}
