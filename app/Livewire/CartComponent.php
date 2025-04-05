<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Item;

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
     // 結帳完成並送出訂單
     public function checkout()
     {
         $orderData = session()->get('cart');
 
         if (!$orderData || empty($orderData['items'])) {
             return; // 若無商品，則不處理
         }
 
        $validItems = [];
        $removedItems = [];
        $outOfStock = [];
        $outOfEnable = [];
    
        foreach ($orderData['items'] as $itemData) {
            $item = Item::find($itemData['id']);
            if (!$item || !$item->enabled) {
                $outOfEnable[] = $itemData['title'];
                $removedItems[] = $itemData['id'];
                continue;
            }
    
            if ($item->stock < $itemData['qty']) {
                $outOfStock[] = $item->title;
                continue;
            }
    
            $validItems[] = [
                'model' => $item,
                'qty' => $itemData['qty'],
            ];
        }
     
        // 有商品已移除或庫存不足
        if (!empty($removedItems) || !empty($outOfStock)) {
            // 購物車過濾掉不能購買的商品
            session()->put('cart.items', array_filter($orderData['items'], function ($item) use ($removedItems, $outOfStock) {
                return !in_array($item['id'], $removedItems);
            }));
    
            $errorMessage = '';

            if (!empty($outOfEnable)) {
                $errorMessage .= '❌ 已停售的商品：' . implode('、', $outOfEnable) . '<br>';
                $errorMessage .= ' 這些商品已自動從您的購物車中移除。<br><br>';
            }
            
            if (!empty($outOfStock)) {
                $errorMessage .= '⚠️ 庫存不足的商品：' . implode('、', $outOfStock) . '<br>';
                $errorMessage .= ' 請調整數量或更換商品喔！<br><br>';
            }
            
            if ($errorMessage !== '') {
                $errorMessage .= '請重新挑選可購買的商品後再試一次～';
                $this->dispatch('showAlert', ['message' => $errorMessage, 'type' => 'error']);
            }
            return;
        }
         
         // 所有商品都有效，建立新訂單
         $order = Order::create([
             'user_id' => 1, //  auth()->id()
             'total_price' => 0, // 先預設為0
         ]);
         
         $totalPrice = 0;
         foreach ($validItems as $data) {
            $item = $data['model'];
            $qty = $data['qty'];
    
            // 建立訂單項目
            $order->items()->attach($item->id, [
                'qty' => $qty,
                'order_price' => $item->price,
            ]);
    
            // 扣庫存
            $item->decrement('stock', $qty);
    
            $totalPrice += $item->price * $qty;
        }
    
        $order->update(['total_price' => $totalPrice]);
    
        session()->forget('cart');
    
        return redirect()->route('orders.payok');
     }
}
