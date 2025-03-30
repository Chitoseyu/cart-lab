<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class CartComponent extends Component
{

    public Order $order;

    public function render()
    {
        return view('livewire.cart-component');
    }
    // 付款成功頁面
    public function payOk()
    {
        return view('page.orders.payok');
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
 
     // 新增：減少購物車中指定商品的數量
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
         $orderData = session()->get('cart');
 
         if (!$orderData || empty($orderData['items'])) {
             return; // 若無商品，則不處理
         }
 
         // 建立新訂單
         $order = Order::create([
             // 'user_id' => auth()->id(),
             'user_id' => 1,
         ]);
 
         // 插入訂單商品
         foreach ($orderData['items'] as $item) {
             $order->items()->attach($item['id'], ['qty' => $item['qty']]);
         }
 
         // 清除 session，防止重複下單
         session()->forget('cart');
         
         return redirect()->route('page.orders.payok');
     }
}
