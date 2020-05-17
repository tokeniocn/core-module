<?php

namespace Modules\Core\Seeds\Frontend;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Config;

/**
 * Class UserTableSeeder.
 */
class ConfigTableSeeder extends Seeder
{

    /**
     * Run the database seed.
     */
    public function run()
    {

        store_config('core::config', [
            'system_logo'=> [
                'key'         => 'system_logo',
                'type'        => 'image',
                'title'       => 'Logo',
                'value'       => '',
                'description' => 'App Logo',
            ],
            'system_name'=> [
                'key'         => 'system_name',
                'type'        => 'text',
                'title'       => '系统名称',
                'value'       => 'App名字',
                'description' => 'App 名字',
            ],
            'cover_images'                  => [
                'key'         => 'cover_images',
                'type'        => 'image_list',
                'title'       => '启动图',
                'value'       => [],
                'description' => 'App启动图',
            ],
            'share_link' => [
                'key'         => 'share_link',
                'type'        => 'text',
                'title'       => '分享链接',
                'value'       => '',
                'description' => '分享链接',
            ],
            'default_coin'                  => [
                'key'         => 'default_coin',
                'type'        => 'text',
                'title'       => '默认币种',
                'value'       => 'USDT',
                'description' => '默认币种',
            ],
            'default_coin_list'             => [
                'key'         => 'default_coin_list',
                'type'        => 'text',
                'title'       => '可用币种列表',
                'value'       => 'USDT|ETH|BTC|EOS',
                'description' => '全部可以用的币种',
            ],
            'ios_version' => [
                'key'         => 'ios_version',
                'type'        => 'text',
                'title'       => '苹果版本号',
                'value'       => '1.0.0',
                'description' => 'IOS Version ',
            ],
            'ios_version_download' => [
                'key'         => 'ios_version_download',
                'type'        => 'text',
                'title'       => '苹果下载地址',
                'value'       => '1.0.0',
                'description' => '正式版下载地址',
            ],
            'ios_version_beta' => [
                'key'         => 'ios_version_beta',
                'type'        => 'text',
                'title'       => '苹果预览版版本号',
                'value'       => '1.0.0',
                'description' => 'IOS Version ',
            ],
            'ios_version_beta_download' => [
                'key'         => 'ios_version_beta_download',
                'type'        => 'text',
                'title'       => '预览版版本号',
                'value'       => '',
                'description' => '预览版下载地址',
            ],
            'android_version' => [
                'key'         => 'android_version',
                'type'        => 'text',
                'title'       => '安卓版本号',
                'value'       => '1.0.0',
                'description' => 'android Version ',
            ],
            'android_version_download' => [
                'key'         => 'android_version_download',
                'type'        => 'text',
                'title'       => '安卓下载地址',
                'value'       => '1.0.0',
                'description' => '正式版下载地址',
            ],
            'android_version_beta' => [
                'key'         => 'android_version_beta',
                'type'        => 'text',
                'title'       => '安卓预览版版本号',
                'value'       => '1.0.0',
                'description' => 'android Version ',
            ],
            'android_version_beta_download' => [
                'key'         => 'android_version_beta_download',
                'type'        => 'text',
                'title'       => '预览版版本号',
                'value'       => '',
                'description' => '预览版下载地址',
            ],
        ], ['schema' => true]);

    }
}
