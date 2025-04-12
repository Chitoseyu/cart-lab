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
        Schema::table('orders', function (Blueprint $table) {
            // 付款資料
            $table->string('payment_method')->nullable()->after('total_price')->comment('付款方式');
            $table->tinyInteger('payment_status')->default(0)->after('payment_method')->comment('付款狀態：0=>未付款，1=>已付款');
            $table->string('payment_transaction_id')->nullable()->after('payment_status')->comment('付款交易編號，由支付平台產生');
            $table->timestamp('paid_at')->nullable()->after('payment_transaction_id')->comment('付款完成時間');

            // 收貨資料
            $table->string('shipping_name')->nullable()->after('paid_at')->comment('收件人姓名');
            $table->string('shipping_phone')->nullable()->after('shipping_name')->comment('收件人電話');
            $table->text('shipping_address')->nullable()->after('shipping_phone')->comment('收件地址');
            $table->string('shipping_zip_code', 10)->nullable()->after('shipping_address')->comment('收件郵遞區號');
            $table->string('shipping_city')->nullable()->after('shipping_zip_code')->comment('收件城市');
            $table->string('shipping_district')->nullable()->after('shipping_city')->comment('收件行政區');
            $table->string('shipping_method')->nullable()->after('shipping_district')->comment('配送方式');
            $table->decimal('shipping_fee', 10, 2)->nullable()->after('shipping_method')->comment('配送費用');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'payment_status', 'payment_transaction_id', 'paid_at',
                'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_zip_code',
                'shipping_city', 'shipping_district', 'shipping_method', 'shipping_fee'
            ]);
        });
    }
};
