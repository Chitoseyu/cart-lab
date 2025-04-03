<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use Illuminate\Http\Request;

class ShopPage extends Component
{

    public $currentPage = 1;

    public function mount(){
        $cart = session('cart', ['items' => []]);
        session(['cart' => $cart]);
    }

    public function render(Request $request)
    {
        $query = Item::where('enabled', 1); // 只顯示啟用的商品

        $page = (int) ($request->input('page') ?? $this->currentPage);

        $this->currentPage = $page; // 更新當前頁數

        $items = $query->paginate(10, ['*'], 'page', $page);

        return view('livewire.shop-page', compact('items'));
    }
    // 新增至購物車
    public function addCart($id)
    {
        $orders = session()->get('cart');

        // 查找商品
        $item = Item::findOrFail($id);

        // 提示訊息
        $info_msg = "";

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
            $info_msg = $item->title . ' 已加入購物車！'; 
        }
        else{
            $info_msg = $item->title . ' 已存在購物車！'; 
        }
        session()->put('cart', $orders);

        // 計算所有商品的數量總和
        $totalQty = 0;
        if(!empty($orders['items'])){
            $totalQty = count($orders['items']);
        }
        $this->dispatch('cartTotalUpdated',  $totalQty);


        // 發出提示事件
        $this->dispatch('showFlexAlert', $info_msg);
    }

    // 從購物車移除
    public function removeCart($id)
    {
        $orders = session()->get('cart');

        // 提示訊息
        $info_msg = "";

        // 檢查商品是否在購物車中
        if (isset($orders['items'][$id])) {
            $info_msg = $orders['items'][$id]['title'] . ' 已從購物車移除！';
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
        $this->dispatch('showFlexAlert',  $info_msg);
    }
   
}
