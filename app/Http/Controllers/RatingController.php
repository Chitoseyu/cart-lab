<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use App\Models\ItemRating;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
    
        $user = auth()->user();
        $item = Item::findOrFail($request->item_id);
    
        $hasPurchased = $user->orders()
            ->whereHas('items', function ($q) use ($item) {
                $q->where('item_id', $item->id);
            })->exists();
    
        $msg = ['type' => 'success','message' => ''];
        if (!$hasPurchased) {
            $msg['type'] = 'error';
            $msg['message'] = '您尚未購買此商品，無法評論。';
            return response()->json($msg);
        }
    
        $review = ItemRating::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $item->id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );
        // 更新商品評分
        Item::updateItemRating($item->id);

        if($review){
            $msg['message'] = '評論成功';
            return response()->json($msg);
        }
    }
}
