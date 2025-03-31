<?php

namespace App\Livewire;

use Livewire\Component;

class CartIcon extends Component
{
    public $cartTotal = 0;

    protected $listeners = ['cartTotalUpdated' => 'updateCartTotal'];

    public function mount()
    {
        $cart = session('cart', ['items' => []]);
        session(['cart' => $cart]);

     
        // 計算所有商品的數量總和
        if(!empty($cart['items'])){
            $this->cartTotal = count($cart['items']);
        }
        $totalQty = $this->cartTotal;
        
        $this->dispatch('cartTotalUpdated',  $totalQty);
    }

    public function updateCartTotal($totalQty)
    {
        $this->cartTotal = $totalQty;
    }
    public function redirectToCart()
    {
        return redirect()->route('page.orders.cartlist');
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}
