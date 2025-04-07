<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\MembershipLevel;

class TransferUserRoleAndLevelSeeder extends Seeder
{
    public function run(): void
    {
        // 預設角色
        $roles = [
            'admin' => '管理員',
            'user' => '一般用戶'
        ];

        foreach ($roles as $name => $display) {
            Role::firstOrCreate(['name' => $name], ['display_name' => $display]);
        }

        // 預設會員等級
        $levels = [
            'normal' => '普通會員',
            'vip' => 'VIP 會員'
        ];

        foreach ($levels as $name => $display) {
            MembershipLevel::firstOrCreate(['name' => $name], [
                'display_name' => $display,
                'discount_rate' => $name === 'vip' ? 10.00 : 0.00
            ]);
        }

        // 若存在舊資料，重新指派到 user 表 
        $defaultRole = Role::where('name', 'user')->first();
        $defaultLevel = MembershipLevel::where('name', 'normal')->first();

        User::query()->chunk(100, function ($users) use ($defaultRole, $defaultLevel) {
            foreach ($users as $user) {
                $user->role_id = $defaultRole->id;
                $user->membership_level_id = $defaultLevel->id;
                $user->save();
            }
        });
    }
}
