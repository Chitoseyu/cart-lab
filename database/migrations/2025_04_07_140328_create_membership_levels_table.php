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
        Schema::create('membership_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('會員等級');
            $table->string('display_name')->nullable()->comment('顯示名稱');
            $table->text('description')->nullable();
            $table->decimal('discount_rate', 5, 2)->default(0.00)->comment('折扣%');
            $table->boolean('free_shipping')->default(false)->comment('是否免運');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_levels');
    }
};
