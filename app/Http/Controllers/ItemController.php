<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Order;

class ItemController extends Controller
{
    public function list()
    {
        return view('page.product.list'); 
    }
    public function detail($id)
    {
        $product = Item::findOrFail($id);

        

        $user = auth()->user();

        $hasPurchased = false;
        if ($user) {
            $hasPurchased = $user->orders()
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('item_id', $product->id);
                })->exists();
    
            // 查詢使用者是否已經對該商品進行評分
            $userReview = $product->ratings()->where('user_id', $user->id)->first();
            if ($userReview && $userReview->comment) {
                $userReview->comment = htmlspecialchars($userReview->comment, ENT_QUOTES);
            }
        } else {
            $userReview = null;
        }
    
        $reviews = $product->ratings()->latest()->with('user')->get();
        $averageRating = $product->rating ?? 0;

    
        return view('page.product.detail', compact('product', 'hasPurchased', 'reviews', 'averageRating', 'userReview'));
    }

    // 顯示商品管理列表
    public function index(Request $request)
    {
        $query = Item::query();
        // 搜尋功能
        if ($request->has('search') && $request->filled('search')) {
            $filterColumn = $request->get('filter_column', 'title'); // 預設搜尋 title
            $searchTerm = $request->get('search');
            
            $query->where($filterColumn, 'LIKE', "%{$searchTerm}%");
            
        }
         // 排序功能
        $sort = $request->get('sort', 'updated_at'); // 預設排序欄位
        $order = $request->get('order', 'desc'); // 預設降序

        // 允許的排序欄位
        $allowedSorts = ['title', 'price', 'stock', 'desc', 'updated_at'];

        $query->orderBy('enabled', 'desc'); // 第一順位：啟用狀態（啟用排在前面）

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $order);
        }
        $items = $query->paginate(10)->appends($request->query());


        return view('page.product.index', compact('items'));
    }

    // 顯示新增/編輯商品表單
    public function form($id = null)
    {
        $item = $id ? Item::findOrFail($id) : null;
        return view('page.product.edit', compact('item'));
    }

     // 儲存新商品
     public function store(Request $request)
     {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'price'   => 'required|int|min:1',
            'stock'   => 'required|integer|min:0',
            'desc'    => 'nullable|string',
            'enabled' => 'required|boolean',
            'pic'     => 'nullable|image|max:2048', // 限制圖片大小 2MB
        ]);

        // 若有上傳圖片
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $format_title = preg_replace('/[^a-zA-Z\p{Han}]+/u', '', $validated['title']); //只保留中英文
            $filename = $format_title . '-' . time() . '.' . $file->getClientOriginalExtension();
           
            // 將檔案移到 storage/public/images/product
            $file->storeAs('images/product', $filename, 'public');
            $validated['pic'] = $filename;
        }
        else{
            $validated['pic'] = "no_image.png";
        }

        // 若未填 discount，預設為 0
        if (!isset($validated['discount'])) {
            $validated['discount'] = 0;
        }

        // 自動計算 discounted_price
        $validated['discounted_price'] = round($validated['price'] * (1 - $validated['discount'] / 100));

        try {
            $item = Item::create($validated);
            $response = ($item) ? ['type'  => 'success','message' => '商品建立成功！']:['type'  => 'error','message' => '商品建立失敗，請稍後再試。'] ;
        } catch (\Exception $e) {
            //$e->getMessage()
            $response = ['type'  => 'error','message' => '商品建立時發生錯誤'];
        }
 
        return redirect()->route('items.index')->with($response);
     }

    // 更新商品
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:100',
            'price'   => 'required|int|min:1',
            'stock'   => 'required|integer|min:0',
            'desc'    => 'nullable|string',
            'enabled' => 'required|boolean',
            'pic'     => 'nullable|image|max:2048',
            'discount' => 'nullable|integer|min:0|max:99',
        ]);
   
        $item = Item::findOrFail($id);

        $updateData = []; // 用於儲存要更新的欄位和值

        // 檢查資料是否發生變化
        foreach ($validated as $key => $value) {
            if ($item->$key != $value) {
                $updateData[$key] = $value;
            }
        }

        // 若有上傳新圖片
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $format_title = preg_replace('/[^a-zA-Z\p{Han}]+/u', '', $validated['title']); //只保留中英文
            $filename = $format_title . '-' . time() . '.' . $file->getClientOriginalExtension();
            // 將檔案移到 storage/public/images/product
            $file->storeAs('images/product', $filename, 'public');
            $updateData['pic'] = $filename;
            // 刪除舊檔案
            if ($item->pic && Storage::disk('public')->exists('images/product/' . $item->pic)) {
                if($item->pic !== "no_image.png"){
                    Storage::disk('public')->delete('images/product/' . $item->pic);
                }  
            }
            $changed = true;
        }

        // 若未填 discount，預設為 0
        if (!isset($validated['discount'])) {
            $validated['discount'] = 0;
        }

        // 自動計算 discounted_price
        $validated['discounted_price'] = round($validated['price'] * (1 - $validated['discount'] / 100));

        // 如果 discounted_price 發生變化，則加入更新資料陣列
        if ($item->discounted_price != $validated['discounted_price']) {
            $updateData['discounted_price'] = $validated['discounted_price'];
        }

       // 只有在有欄位需要更新時才執行 update()
       if (!empty($updateData)) {
            try {
                $result = $item->update($updateData);
                if ($result) {
                    $response =  ['type' => 'success', 'message' => '商品更新成功！'];
                } else {
                    $response =  ['type' => 'error', 'message' => '商品更新失敗！'];
                }
            } catch (\Exception $e) {
                $response = ['type' => 'error', 'message' => '商品更新失敗：']; // $e->getMessage()
            }
        } else {
            // 沒有資料需要更新
            $response = ['type' => 'success', 'message' => '商品資料未變更！'];
        }
        
        return redirect()->route('items.index')->with($response);
    }
    // 更新商品狀態
    public function toggleStatus($id)
    {
        $item = Item::findOrFail($id);
        $item->enabled = !$item->enabled;
        $item->save();

        return response()->json([
            'success' => true,
            'new_status' => $item->enabled ? 1 : 0
        ]);
    }
    // 刪除商品
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        // 刪除檢查：圖片是否存在於 storage/public/images/product
        if ($item->pic && Storage::disk('public')->exists('images/product/' . $item->pic)) {
            if($item->pic !== "no_image.png"){
                Storage::disk('public')->delete('images/product/' . $item->pic);
            }   
        }
        try {
            $item->delete();
    
            $response = [
                'type'  => 'success',
                'message' => '商品已刪除！'
            ];
        } catch (\Exception $e) {
            $response = [
                'type'  => 'error',
                'message' => '商品刪除失敗，請稍後再試！'
            ];
        }

        return redirect()->route('items.index')->with($response);
    }


}
