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
            $table->foreignId('role_id')->nullable()->after('login_ip')->constrained()->nullOnDelete()->comment('角色ID');
            $table->foreignId('membership_level_id')->nullable()->after('role_id')->constrained()->nullOnDelete()->comment('會員等級ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['membership_level_id']);
            $table->dropColumn(['role_id', 'membership_level_id']);
        });
    }
};
