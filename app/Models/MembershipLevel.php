<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipLevel extends Model
{
    protected $fillable = ['name', 'display_name', 'description', 'discount_rate', 'free_shipping'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}