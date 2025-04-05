<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'price', 'pic', 'desc', 'enabled', 'stock'];

    public function orders(){
        return $this->belongsToMany(\App\Models\Order::class)->withTimestamps();
    }
    public function getPriceWithQtyAttribute()
    {
        return $this->price * ($this->pivot->qty ?? 1);
    }

}
