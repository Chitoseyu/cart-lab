<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_order', function (Blueprint $table) {
            $table->decimal('order_price', 10, 2)->default(0)->comment('下單時商品價格')->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_order', function (Blueprint $table) {
            $table->dropColumn('order_price');
        });
    }
};
