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
        return $this->belongsToMany(\App\Models\Item::class)->withTimestamps()->withPivot('qty');
    }
    public function getSumAttribute(){
        $orderItems = $this->items;
        $sum = 0;
        foreach($orderItems as $item){
            $sum += ($item->price * $item->pivot->qty);
        }
        return $sum;
    }

}
