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
        Config::create([
            'key' => 'config',
            'value' => [
                [
                    'key' => 'system_logo',
                    'type' => 'image',
                    'title' => 'Logo',
                    'value' => '/storage/7b08d6cdc6ab2af00c3a423a0ec2d6ea.png',
                    'description' => 'App Logo',
                ],
                [
                    'key' => 'system_name',
                    'type' => 'text',
                    'title' => '系统名称',
                    'value' => 'App名字',
                    'description' => 'App 名字',
                ],
                [
                    'key' => 'start_image',
                    'type' => 'image_list',
                    'title' => '启动图',
                    'value' =>
                        [
                            '/storage/6e45bbf0a84e86e38090bb1fd505cc16.png',
                            '/storage/6806bb52e97bc48c336e22e80c7e9e3e.png',
                            '/storage/f8adf9a78fe721a10797b9a02995141f.png',
                        ],
                    'description' => 'App启动图',
                ],
                [
                    'key' => 'share_link',
                    'type' => 'text',
                    'title' => '分享链接',
                    'value' => 'https://tysh.com/register?c=12345',
                    'description' => '分享链接',
                ],
                [
                    'key' => 'default_coin',
                    'type' => 'text',
                    'title' => '默认币种',
                    'value' => 'USDT',
                    'description' => '默认币种',
                ],
                [
                    'key' => 'default_coin_list',
                    'type' => 'text',
                    'title' => '可用币种列表',
                    'value' => 'USDT|ETH|BTC|EOS',
                    'description' => '全部可以用的币种',
                ],
                [
                    'key' => 'ios_version',
                    'type' => 'text',
                    'title' => '苹果版本号',
                    'value' => '1.0.0',
                    'description' => 'IOS Version ',
                ],
                [
                    'key' => 'ios_version_download',
                    'type' => 'text',
                    'title' => '苹果下载地址',
                    'value' => '1.0.0',
                    'description' => '正式版下载地址',
                ],
                [
                    'key' => 'ios_version_beta',
                    'type' => 'text',
                    'title' => '苹果预览版版本号',
                    'value' => '1.0.0',
                    'description' => 'IOS Version ',
                ],
                [
                    'key' => 'ios_version_beta_download',
                    'type' => 'text',
                    'title' => '预览版版本号',
                    'value' => '/storage/5fe6b1e361530a28c7c942e1e41a1a51-12.jpg',
                    'description' => '预览版下载地址',
                ],
                [
                    'key' => 'android_version',
                    'type' => 'text',
                    'title' => '安卓版本号',
                    'value' => '1.0.0',
                    'description' => 'android Version ',
                ],
                [
                    'key' => 'android_version_download',
                    'type' => 'text',
                    'title' => '安卓下载地址',
                    'value' => '1.0.0',
                    'description' => '正式版下载地址',
                ],
                [
                    'key' => 'android_version_beta',
                    'type' => 'text',
                    'title' => '安卓预览版版本号',
                    'value' => '1.0.0',
                    'description' => 'android Version ',
                ],
                [
                    'key' => 'android_version_beta_download',
                    'type' => 'text',
                    'title' => '预览版版本号',
                    'value' => '/storage/5fe6b1e361530a28c7c942e1e41a1a51-12.jpg',
                    'description' => '预览版下载地址',
                ]
            ],
            'module' => 'core',
            'remark' => 'App Config',
        ]);
    }
}
