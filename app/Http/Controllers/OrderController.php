<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        $query = Order::with('items')->has('items'); // 過濾掉沒有商品的訂單


        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                // 搜尋訂單編號
                $q->where('id', $searchTerm)
                  // 或是商品名稱
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
}
