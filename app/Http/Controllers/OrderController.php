<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 訂單列表
    public function list(Request $request)
    {
        $query = Order::with('items')->has('items'); // 過濾掉沒有商品的訂單
        $user = Auth::user();
    
        // 判斷登入使用者的角色 ID
        if ($user->role_id != 1) {
            $query->where('user_id', $user->id);
        }
    
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                // 搜尋訂單編號
                $q->where('id', $searchTerm)
                    // 商品名稱
                    ->orWhereHas('items', function ($q) use ($searchTerm) {
                        $q->where('title', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }
    
        $orders = $query->latest()->paginate(10)->appends($request->query());
    
        return view('page.orders.list', compact('orders'));
    }
    // 刪除訂單
    public function destroy($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $user = Auth::user();

        // 權限檢查
        if ($user->role_id != 1 && $order->user_id != $user->id) {
            return redirect()->route('orders.list')
                ->with([
                    'message' => '沒有權限刪除此訂單！',
                    'type' => 'error'
                ]);
        }

        try {
            // 回復庫存數量
            foreach ($order->items as $item) {
                $item->increment('stock', $item->pivot->qty);
            }
    
            // 刪除訂單
            $order->delete();
    
            return redirect()->route('orders.list')
                            ->with([
                                'message' => '訂單已成功刪除！',
                                'type' => 'success'
                            ]);
        } catch (\Exception $e) {
            return redirect()->route('orders.list')
                            ->with([
                                'message' => '刪除訂單失敗，請稍後再試！',
                                'type' => 'error'
                            ]);
        }
    }
    // 批次刪除訂單
    public function bulkDelete(Request $request)
    {
        $orderIds = $request->order_ids;
        
        if ($orderIds) {
            Order::whereIn('id', $orderIds)->delete();

            return response()->json(['message' => '選取的訂單已刪除']);
        }

        return response()->json(['message' => '未選取任何訂單'], 400);
    }
    // 手動更新訂單狀態
    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();

        // 權限檢查
        if ($user->role_id != 1) {
            return response()->json(['success' => false, 'message' => '沒有權限更改訂單狀態'], 403);
        }

        $order->status = $request->input('status');
        $order->save();

        return response()->json(['success' => true]);
    }
  
    // 查詢銷售前三的商品資訊，依照訂單中購買的數量總和排序
    public function getTopSellingProducts(Request $request)
    {
        $topProducts = DB::table('item_order')
        ->join('items', 'item_order.item_id', '=', 'items.id')
        ->select(
            'items.id',
            'items.title',
            'items.pic',
            'items.price',
            'items.discount',
            'items.discounted_price',
            'items.rating',
            DB::raw('SUM(item_order.qty) as total_qty')
        )
        ->groupBy(
            'items.id',
            'items.title',
            'items.pic',
            'items.price',
            'items.discount',
            'items.discounted_price',
            'items.rating'
        )
        ->orderByDesc('total_qty')
        ->limit(3)
        ->get();

        return response()->json($topProducts);
    }
    // 隨機顯示商品
    public function randomProducts(Request $request)
    {
        $info_field = ['id', 'title', 'pic', 'price', 'discount', 'discounted_price', 'rating'];
        // 只選取已啟用且有庫存的商品
        $products = Item::where('enabled', 1)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit(3)
            ->get($info_field);

        // 預設 total_qty 為 0
        $products->each(function ($product) {
            $product->total_qty = 0;
        });

        return response()->json($products);
    }
    // 立即購買
    public function directCheckout(Request $request)
    {
        $orders = session()->get('cart', ['items' => []]);
        $item = Item::findOrFail($request->input('product_id'));

            if (!isset($orders['items'][$item->id])) {
            $orders['items'][$item->id] = [
                'id' => $item->id,
                'title' => $item->title,
                'price' => $item->raw_price,
                'pic' => $item->pic,
                'qty' => 1,
            ];
        }
        session()->put('cart', $orders);

        
        return redirect()->route('orders.cartlist');
    }
    // 加入購物車
    public function addToCart(Request $request)
    {
        $orders = session()->get('cart', ['items' => []]);
        $item = Item::findOrFail($request->input('product_id'));

        // 庫存檢查
        $item_stock = $item->stock;
        if( $item_stock > 0){
            if (!isset($orders['items'][$item->id])) {
                $orders['items'][$item->id] = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'price' => $item->raw_price,
                    'pic' => $item->pic,
                    'qty' => 1,
                ];
                $message = "{$item->title} 已加入購物車！";
            } else {
                $message = "{$item->title} 已存在購物車！";
            }
        }
        else{
            $message = "{$item->title} 沒有庫存囉！";
        }

        session()->put('cart', $orders);

        $response = ['type'  => 'success','message' => $message];
        
        return redirect()->back()->with($response);
    }
    // 付款成功頁面
    public function payOk()
    {
        return view('page.orders.payok');
    }
    // 結帳頁面
    public function checkoutPage()
    {
        if (!session()->has('items') || !session()->has('totalPrice')) {
            $response = [
                'type' => 'error',
                'message' => '找不到結帳資料，請重新操作。',
            ];      
            // 重新導向到購物車頁面
            return redirect()->route('orders.cartlist')->with($response);
        }
        // 從 session 中獲取資料
        $items = session('items');
        $totalPrice = session('totalPrice');

        return view('page.orders.checkout', compact('items', 'totalPrice'));
    }
    // 新增訂單資料
    public function addToOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);


        // 檢查付款資訊並對應訂單編號儲存


        $orderData = session('cart');
        if (!$orderData || empty($orderData['items'])) {
            return redirect()->route('orders.cartlist')->withErrors('購物車是空的');
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => 0,
        ]);

        $totalPrice = 0;
        foreach ($orderData['items'] as $itemData) {
            $item = Item::find($itemData['id']);
            if (!$item || $item->stock < $itemData['qty']) continue;

            $order->items()->attach($item->id, [
                'qty' => $itemData['qty'],
                'order_price' => $item->raw_price,
            ]);

            // 減庫存
            $item->decrement('stock', $itemData['qty']);
            $totalPrice += $item->raw_price * $itemData['qty'];
        }

        $order->update(['total_price' => $totalPrice]);

        session()->forget('cart');

        return redirect()->route('orders.payok');
    }

}
