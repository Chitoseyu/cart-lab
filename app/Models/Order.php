<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'comment', 'status'];

    // 訂單有哪些商品
    public function items(){
        return $this->belongsToMany(\App\Models\Item::class)->withTimestamps()->withPivot('qty', 'order_price'); 
    }
    public function getSumAttribute(){
        return $this->items->sum(function($item) {
            return $item->pivot->order_price * $item->pivot->qty;
        });
    }
    // 訂單的用戶
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
