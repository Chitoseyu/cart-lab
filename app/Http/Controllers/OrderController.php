<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
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

}
