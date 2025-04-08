<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;

class ItemController extends Controller
{
    public function list()
    {
        return view('page.product.list'); 
    }
    public function detail($id)
    {
        $product = Item::findOrFail($id);
        return view('page.product.detail', compact('product')); 
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
                    'price' => $item->price,
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
     // 立即購買
     public function directCheckout(Request $request)
     {
        $orders = session()->get('cart', ['items' => []]);
        $item = Item::findOrFail($request->input('product_id'));
 
         if (!isset($orders['items'][$item->id])) {
            $orders['items'][$item->id] = [
                'id' => $item->id,
                'title' => $item->title,
                'price' => $item->price,
                'pic' => $item->pic,
                'qty' => 1,
            ];
        }
        session()->put('cart', $orders);
 
      
        return redirect()->route('orders.cartlist');
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
        $allowedSorts = ['title', 'price', 'stock', 'desc', 'enabled', 'updated_at'];

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
        ]);
   
        $item = Item::findOrFail($id);

        // 若有上傳新圖片
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $format_title = preg_replace('/[^a-zA-Z\p{Han}]+/u', '', $validated['title']); //只保留中英文
            $filename = $format_title . '-' . time() . '.' . $file->getClientOriginalExtension();
            // 將檔案移到 storage/public/images/product
            $file->storeAs('images/product', $filename, 'public');
            $validated['pic'] = $filename;
            // 刪除舊檔案
            if ($item->pic && Storage::disk('public')->exists('images/product/' . $item->pic)) {
                if($item->pic !== "no_image.png"){
                    Storage::disk('public')->delete('images/product/' . $item->pic);
                }  
            }
        }

        $result = $item->update($validated);

        $response = ($result) ? ['type'  => 'success','message' => '商品更新成功！']:['type'  => 'success','message' => '商品更新失敗！'];


        
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
