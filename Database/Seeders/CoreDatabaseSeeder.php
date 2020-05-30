<?php

namespace Modules\Core\Database\Seeders\CoreDatabaseSeeder;

use Illuminate\Database\Seeder;
use Modules\Core\Database\Seeders\Admin\AdminUserTableSeeder;
use Modules\Core\Database\Seeders\Admin\AdminMenuTableSeeder;
use Modules\Core\Database\Seeders\Admin\AdminRolePermissionTableSeeder;
use Modules\Core\Database\Seeders\Frontend\Auth\UserTableSeeder;
use Modules\Core\Database\Seeders\Frontend\ConfigTableSeeder;
use Modules\Core\Database\Seeders\Frontend\LabelDataSeeder;


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
