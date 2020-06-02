<?php

namespace Modules\Core\Database\Seeders\Admin;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = AdminUser::create([
            'username' => 'admin',
            'password' => 'admin',
            'active' => 1,
        ]);

        $admin->assignRole('admin');
    }
}
