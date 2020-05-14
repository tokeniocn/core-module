<?php

namespace Modules\Core\Seeds\Admin;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Admin\AdminMenu;

class AdminMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = AdminMenu::create([
            'title' => '用户管理',
            'icon' => 'layui-icon-user',
            'url' => '',
            'status' => 1,
        ]);

        $system = AdminMenu::create([
            'title' => '系统管理',
            'icon' => 'icon-set',
            'url' => '',
            'status' => 1,
        ]);

        $role = AdminMenu::create([
            'title' => '角色权限',
            'parent_id' => $system->id,
            'url' => route('admin.auth.roles', [], false),
            'status' => 1,
        ]);

        $module = AdminMenu::create([
            'title' => '模块管理',
            'parent_id' => $system->id,
            'url' => route('admin.module.modules', [], false),
            'status' => 1,
        ]);

//        $queue = AdminMenu::create([
//            'title' => '队列监控',
//            'parent_id' => $system->id,
//            'url' => route('horizon.index', ['view' => 'dashboard'], false),
//            'status' => 1,
//        ]);
    }
}
