<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'pic',
        'desc',
        'price',
        'stock',
        'sell_at',
        'enabled',
        'rating',
        'discount',
        'discounted_price',
    ];

    public function orders(){
        return $this->belongsToMany(\App\Models\Order::class)->withTimestamps();
    }
    public function ratings()
    {
        return $this->hasMany(ItemRating::class);
    }
    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
    public function getPriceWithQtyAttribute()
    {
        return $this->price * ($this->pivot->qty ?? 1);
    }
    // 格式化 price 欄位
    public function getPriceAttribute($value)
    {
        return number_format($value);
    }

    // 格式化 discounted_price 欄位
    public function getDiscountedPriceAttribute($value)
    {
        return number_format($value);
    }
    // 取得原始 price 欄位，用於編輯表單
    public function getRawPriceAttribute()
    {
        return $this->attributes['price'];
    }

    // 取得原始 discounted_price 欄位，用於編輯表單
    public function getRawDiscountedPriceAttribute()
    {
        return $this->attributes['discounted_price'];
    }

    // 移除 price 欄位的千分位符號
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = str_replace(',', '', $value);
    }

    // 移除 discounted_price 欄位的千分位符號
    public function setDiscountedPriceAttribute($value)
    {
         $this->attributes['discounted_price'] = str_replace(',', '', $value);
    }
    public static function updateItemRating($itemId)
    {
        $average = ItemRating::where('item_id', $itemId)->avg('rating') ?? 0;
        self::where('id', $itemId)->update(['rating' => round($average, 1)]);
    }

}
