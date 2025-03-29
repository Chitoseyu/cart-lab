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
        Schema::create('item_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->index();
            $table->foreign('order_id')->references('id')->on("orders")->onDelete("cascade");
            $table->unsignedBigInteger('item_id')->index();
            $table->foreign('item_id')->references('id')->on("items")->onDelete("cascade");
            $table->unsignedInteger('qty')->default(1);
            $table->string('desc',500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_order',function(Blueprint $table){
            $table->dropForeign(['item_id']);
            $table->dropForeign(['order_id']);
        });
        Schema::dropIfExists('item_order');
    }
};
