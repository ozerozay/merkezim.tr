<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ShopPackage;
use App\Models\ShopService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartManager
{
    protected $sessionKey = 'cart';

    public function getCartCount()
    {
        return $this->getCart()->count();
    }

    public function addItem($product, $quantity = 1) {}

    protected function addItemToDatabase($product, $quantity)
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
            if ($item->buy_max > 0 && $item->buy_max < ($existingCartItem->quantity + $quantity)) {
                $existingCartItem->quantity = $item->buy_max;
            } else {
                $existingCartItem->quantity += $quantity;
            }
            $existingCartItem->save();
        } else {
            $cartItem = new CartItem([
                'item_id' => $item->id,
                'item_type' => get_class($item),
                'quantity' => ($item->buy_max > 0 && $item->buy_max < $quantity) ? $item->buy_max : $quantity,
                'price' => $item->price,
            ]);
            $cart->items()->save($cartItem);
        }
    }

    protected function addToSession(array $product, int $quantity): void
    {
        $cart = session()->get($this->sessionKey, []);
        $productId = $product['id'];

        $item = $this->getItem($product['type'], $productId);

        // Sepette zaten var mı kontrolü,
        if (isset($cart[$productId])) {
            if ($item->buy_max > 0 && $item->buy_max < $cart[$productId]['quantity'] + $quantity) {
                $cart[$productId]['quantity'] = $item->buy_max;
            } else {
                $cart[$productId]['quantity'] += $quantity;
            }
        } else {
            if ($item->buy_max > 0 && $item->buy_max < $quantity) {
                $cart[$productId] = array_merge($product, ['quantity' => $item->buy_max]);
            } else {
                $cart[$productId] = array_merge($product, ['quantity' => $quantity]);
                //$cart[$productId]['quantity']->quantity += $quantity;
            }

        }

        session()->put($this->sessionKey, $cart);
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

    public function getCart()
    {
        return Auth::check() ? $this->getDatabaseCart() : $this->getSessionCart();
    }

    public function getDatabaseCart()
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()])->with('items')->get();
    }

    public function getSessionCart(): Collection
    {
        return collect(session()->get($this->sessionKey, []));
    }

    protected function getItem($type, $id)
    {
        if ($type === 'package') {
            return ShopPackage::findOrFail($id);
        } elseif ($type === 'service') {
            return ShopService::findOrFail($id);
        }

        throw new \Exception('Lütfen tekrar deneyin.');
    }
}
