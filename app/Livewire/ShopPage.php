<?php

namespace App\Livewire;

use Livewire\Component;
use Log;
use App\Models\Order;
use App\Models\Item;

class ShopPage extends Component
{
    public $items;
    public Order $order;

    public function mount(){
        $this->items = Item::get();
        $order = Order::create(['user_id'=>1]);
        session(['order'=>$order]);
    }

    public function render()
    {
        return view('livewire.shop-page')->layout('layouts.app');
    }
    public function payOk()
    {
        return view('page.orders.payok');
    }
    public function addCart($id)
    {
        $orders = session()->get('cart', ['items' => []]);

        // 查找商品
        $item = Item::findOrFail($id);

        // 檢查商品是否已在購物車中
        if (isset($orders['items'][$id])) {
            // 若商品已存在，數量 +1
            $orders['items'][$id]['qty'] += 1;
        } else {
            // 若商品不存在，新增
            $orders['items'][$id] = [
                'id' => $item->id,
                'title' => $item->title,
                'price' => $item->price,
                'qty' => 1,
            ];
        }
        // 存回 session
        session()->put('cart', $orders);
    }
    // 新增：增加購物車中指定商品的數量
    public function increaseCart($id)
    {
        $orders = session()->get('cart', ['items' => []]);
        if (isset($orders['items'][$id])) {
            $orders['items'][$id]['qty'] += 1;
        }
        session()->put('cart', $orders);
    }

    // 新增：減少購物車中指定商品的數量，若數量為 0 則移除該商品
    public function decreaseCart($id)
    {
        $orders = session()->get('cart', ['items' => []]);
        if (isset($orders['items'][$id])) {
            $orders['items'][$id]['qty'] -= 1;
            if ($orders['items'][$id]['qty'] < 1) {
                unset($orders['items'][$id]);
            }
        }
        session()->put('cart', $orders);
    }
    public function checkout()
    {
        // 取得 session 訂單
        $orderData = session()->get('cart');
        // dd($orderData);

        if (!$orderData || empty($orderData['items'])) {
            return; // 若無商品，則不處理
        }

        // 建立新訂單
        $order = Order::create([
            // 'user_id' => auth()->id(), // 假設需要關聯用戶
            'user_id' => 1,
        ]);

        // 插入訂單商品
        foreach ($orderData['items'] as $item) {
            $order->items()->attach($item['id'], ['qty' => $item['qty']]);
        }

        // 清除 session，防止重複下單
        session()->forget('cart');

        return redirect()->route('page.orders.payok'); // 假設有結帳成功頁面
    }
}
