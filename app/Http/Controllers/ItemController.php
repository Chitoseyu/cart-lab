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


}
