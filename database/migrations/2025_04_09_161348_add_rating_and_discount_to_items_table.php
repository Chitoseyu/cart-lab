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
       
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('rating', 2, 1)->nullable()->after('enabled')->comment('評分');
            $table->unsignedInteger('discounted_price')->nullable()->after('price')->comment('折扣後價格');
            $table->unsignedTinyInteger('discount')->nullable()->after('discounted_price')->comment('折扣百分比 (0~100)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['rating', 'discounted_price', 'discount']);
        });
    }
};
