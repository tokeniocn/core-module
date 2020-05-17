<?php

namespace Modules\Core\Seeds;

use Illuminate\Database\Seeder;

/**
 * 基本表格设置
 */
class BaseTableSeeder extends Seeder
{

    /**
     * Run the database seed.
     */
    public function run()
    {
        store_config('api::settings', [
            'app_logo' => '',
            'app_name' => '',
            'app_cover_images' => [],
            'share_link' => '',
            'default_coin' => 'USDT',
            'default_coin_list' => ['USDT', 'ETH', 'BTC'],
            'ios_version' => '',
            'ios_download' => '',
            'android_version' => '',
            'android_download' => '',
        ]);
    }
}
