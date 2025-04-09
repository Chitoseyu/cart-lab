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
    public function getPriceWithQtyAttribute()
    {
        return $this->price * ($this->pivot->qty ?? 1);
    }

}
