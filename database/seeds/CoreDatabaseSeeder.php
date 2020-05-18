<?php

namespace Modules\Core\Seeds;

use Illuminate\Database\Seeder;
use Modules\Core\Seeds\Admin\AdminUserTableSeeder;
use Modules\Core\Seeds\Admin\AdminMenuTableSeeder;
use Modules\Core\Seeds\Admin\AdminRolePermissionTableSeeder;
use Modules\Core\Seeds\Frontend\Auth\UserTableSeeder;
use Modules\Core\Seeds\Frontend\ConfigTableSeeder;
use Modules\Core\Seeds\Frontend\LabelDataSeeder;


class CoreDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Global
        $this->call(BaseTableSeeder::class);

        //  Admin
        $this->call(AdminRolePermissionTableSeeder::class);
        $this->call(AdminUserTableSeeder::class);
        $this->call(AdminMenuTableSeeder::class);
        $this->call(LabelDataSeeder::class);
        // Frontend
        $this->call(UserTableSeeder::class);
    }
}
