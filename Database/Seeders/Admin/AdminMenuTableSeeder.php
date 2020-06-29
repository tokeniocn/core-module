<?php

namespace Modules\Core\Database\Seeders\Admin;

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
        // 用户管理
        $user = AdminMenu::create([
            'title' => '用户管理',
            'icon' => '',
            'url' => '',
            'status' => 1,
        ]);

        $user = AdminMenu::create([
            'title' => '实名认证',
            'icon' => '',
            'parent_id' => $user->id,
            'url' => route('admin.certify.index', [], false),
            'status' => 1,
        ]);

        $system_config = AdminMenu::create([
            'title' => '注册设置',
            'parent_id' => $user->id,
            'icon' => '',
            'url' => route('admin.system.settings.index', [], false),
            'status' => 1,
        ]);


        // 系统管理
        $system = AdminMenu::create([
            'title' => '系统管理',
            'icon' => '',
            'url' => '',
            'status' => 1,
        ]);

        $system_role = AdminMenu::create([
            'title' => '角色权限',
            'parent_id' => $system->id,
            'icon' => '',
            'url' => route('admin.auth.roles', [], false),
            'status' => 1,
        ]);

        $system_module = AdminMenu::create([
            'title' => '模块管理',
            'parent_id' => $system->id,
            'icon' => '',
            'url' => route('admin.module.modules', [], false),
            'status' => 1,
        ]);

        $system_generator = AdminMenu::create([
            'title' => '代码生成',
            'parent_id' => $system->id,
            'icon' => '',
            'url' => route('admin.generator.index', [], false),
            'status' => 1,
        ]);

        // APP设置
        $app = AdminMenu::create([
            'title' => 'App配置',
            'icon' => '',
            'url' => '',
            'status' => 1,
        ]);

        $app_settings = AdminMenu::create([
            'title' => '基本设置',
            'parent_id' => $app->id,
            'icon' => '',
            'url' => route('admin.app.settings.index', [], false),
            'status' => 1,
        ]);

        $label_setting = AdminMenu::create([
            'title' => 'Label设置',
            'parent_id' => $app->id,
            'icon' => '',
            'url' => route('admin.label.index', [], false),
            'status' => 1,
        ]);
        $announce = AdminMenu::create([
            'title' => '公告设置',
            'parent_id' => $app->id,
            'icon' => '',
            'url' => route('admin.announce.index', [], false),
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
