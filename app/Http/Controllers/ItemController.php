<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        session()->put('cart', $orders);
        
        return redirect()->back();
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
    public function index()
    {
        $items = Item::orderBy('created_at', 'desc')->get();
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
            'price'   => 'required|int|min:0',
            'desc'    => 'required|string',
            'enabled' => 'required|boolean',
            'pic'     => 'nullable|image|max:2048', // 限制圖片大小 2MB
        ]);

        // 若有上傳圖片
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $format_title = preg_replace('/[^a-zA-Z\p{Han}]+/u', '', $validated['title']); //只保留中英文
            $filename = $format_title . '-' . time() . '.' . $file->getClientOriginalExtension();
           
            // 將檔案移到 public/images/product
            $file->move(public_path('images/product'), $filename);
            $validated['pic'] = $filename;
        }

        try {
            $item = Item::create($validated);
            $message = ($item) ? '商品建立成功！' : '商品建立失敗，請稍後再試。';
        } catch (\Exception $e) {
            //$e->getMessage()
            $message = '商品建立時發生錯誤：';
        }
 
        return redirect()->route('items.index')->with('message', $message);
     }

    // 更新商品
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:100',
            'price'   => 'required|int|min:1',
            'desc'    => 'required|string',
            'enabled' => 'required|boolean',
            'pic'     => 'nullable|image|max:2048',
        ]);
   
        $item = Item::findOrFail($id);

        // 若有上傳新圖片處理上傳
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $format_title = preg_replace('/[^a-zA-Z\p{Han}]+/u', '', $validated['title']); //只保留中英文
            $filename = $format_title . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/product'), $filename);
            $validated['pic'] = $filename;
            // 刪除舊檔案的邏輯
        }

        $result = $item->update($validated);

        $message = ($result) ? '商品更新成功！':'商品更新失敗！';


        
        return redirect()->route('items.index')->with('message', $message);
    }
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        // 刪除舊檔案的邏輯
        $item->delete();

        return redirect()->route('items.index')->with('message', '商品已刪除！');
    }


}
