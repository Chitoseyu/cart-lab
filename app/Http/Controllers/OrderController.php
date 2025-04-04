<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function list()
    {
        $orders = Order::with('items')
                    ->has('items') // 過濾掉沒有商品的訂單
                    ->latest()
                    ->paginate(10);

        return view('page.orders.list', compact('orders'));
    }
    // 刪除訂單
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        try {
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
