<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['title' => '牛仔褲', 'price' => 500],
            ['title' => 'T恤', 'price' => 300],
            ['title' => '運動鞋', 'price' => 1200],
            ['title' => '背包', 'price' => 800],
            ['title' => '帽子', 'price' => 250],
            ['title' => '夾克', 'price' => 1500],
            ['title' => '長袖襯衫', 'price' => 700],
            ['title' => '短褲', 'price' => 400],
            ['title' => '皮帶', 'price' => 350],
            ['title' => '太陽眼鏡', 'price' => 900],
        ];

        foreach ($items as $key=> $item) {
            Item::firstOrCreate(
                ['title' => $item['title']], // 檢查條件
                [
                    'price' => $item['price'],
                    'pic' => $key . '.png', // 產生圖片名稱
                ]
            );
        }
    }
}
