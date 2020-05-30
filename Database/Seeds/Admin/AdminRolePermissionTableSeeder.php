<?php

namespace Modules\Core\Database\Seeds\Admin;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Admin\AdminRole;

class AdminRolePermissionTableSeeder extends Seeder
{

    public function run()
    {

        // Create Roles
        AdminRole::create([
            'name' => 'admin',
            'title' => '管理员',
            'guard_name' => 'admin'
        ]);

        AdminRole::create([
            'name' => 'user',
            'title' => '普通用户',
            'guard_name' => 'admin'
        ]);
    }
}
