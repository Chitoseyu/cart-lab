<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'total_price', 
        'comment', 
        'status',

        'payment_method',
        'payment_status',
        'payment_transaction_id',
        'paid_at',

        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_zip_code',
        'shipping_city',
        'shipping_district',
        'shipping_method',
        'shipping_fee',
    ];

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
