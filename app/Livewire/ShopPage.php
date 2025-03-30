<?php

namespace App\Livewire;

use Livewire\Component;
use Log;
use App\Models\Item;

class ShopPage extends Component
{
    public $items;
    

    public function mount(){
        $this->items = Item::get();
        $cart = session('cart', ['items' => []]);
        session(['cart' => $cart]);
    }

    public function render()
    {
        return view('livewire.shop-page')->layout('layouts.app');
    }
    // 新增至購物車
    public function addCart($id)
    {
        $orders = session()->get('cart', ['items' => []]);

        // 查找商品
        $item = Item::findOrFail($id);

        // 檢查商品是否已在購物車中
        if (!isset($orders['items'][$id])) {
            // 若商品不存在，新增
            $orders['items'][$id] = [
                'id' => $item->id,
                'title' => $item->title,
                'price' => $item->price,
                'pic' => $item->pic,
                'qty' => 1,
            ];
        }
        session()->put('cart', $orders);

        // 計算所有商品的數量總和
        $totalQty = 0;
        if(!empty($orders['items'])){
            $totalQty = count($orders['items']);
        }
        $this->dispatch('cartTotalUpdated',  $totalQty);
        // 發出加入提示事件
        $this->dispatch('itemAddedToCart', '已加入購物車');
    }

    // 從購物車移除
    public function removeCart($id)
    {
        $orders = session()->get('cart', ['items' => []]);

        // 檢查商品是否在購物車中
        if (isset($orders['items'][$id])) {
            // 從購物車移除商品
            unset($orders['items'][$id]);
        }
        session()->put('cart', $orders);

        // 計算所有商品的數量總和
        $totalQty = 0;
        if(!empty($orders['items'])){
            $totalQty = count($orders['items']);
        }
        $this->dispatch('cartTotalUpdated',  $totalQty);
        // 發出移除提示事件
        $this->dispatch('itemRemovedFromCart', '已從購物車移除');
    }
   
}
