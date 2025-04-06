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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email')->comment('電話號碼');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('phone')->comment('性別');
            $table->date('birthday')->nullable()->after('gender')->comment('生日');
            $table->string('avatar')->nullable()->after('birthday')->comment('頭像圖片路徑');
            $table->text('address')->nullable()->after('avatar')->comment('地址');
            $table->string('zip_code', 10)->nullable()->after('address')->comment('郵遞區號');
            $table->string('city')->nullable()->after('zip_code')->comment('城市');
            $table->string('district')->nullable()->after('city')->comment('行政區');
            $table->boolean('status')->default(true)->after('district')->comment('帳號是否啟用');
            $table->timestamp('last_login_at')->nullable()->after('status')->comment('上次登入時間');
            $table->string('login_ip')->nullable()->after('last_login_at')->comment('上次登入 IP');
            $table->boolean('is_subscribed')->default(false)->after('login_ip')->comment('是否訂閱電子報');
            $table->string('role')->default('user')->after('is_subscribed')->comment('使用者角色');
            $table->string('provider')->nullable()->after('role')->comment('第三方登入平台名稱');
            $table->string('provider_id')->nullable()->after('provider')->comment('第三方登入平台使用者 ID');
            $table->string('membership_level')->default('normal')->after('provider_id')->comment('會員等級');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'gender', 'birthday', 'avatar', 'address', 'zip_code', 'city', 'district',
                'status', 'last_login_at', 'login_ip', 'is_subscribed', 'role',
                'provider', 'provider_id', 'membership_level',
            ]);
        });
    }
};
