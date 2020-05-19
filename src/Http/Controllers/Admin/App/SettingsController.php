<?php

namespace Modules\Core\Http\Controllers\Admin\App;

use Modules\Coin\Services\CoinService;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Config\ConfigRequest;

/**
 * Class SettingsController
 */
class SettingsController extends Controller
{

    protected $schema = [
        'app_logo' => [
            'key' => 'app_logo',
            'type' => 'image',
            'title' => 'Logo',
            'value' => '',
            'description' => 'App Logo',
        ],
        'app_name' => [
            'key' => 'app_name',
            'type' => 'text',
            'title' => '系统名称',
            'value' => 'App名字',
            'description' => 'App 名字',
        ],
        'app_cover_images' => [
            'key' => 'cover_images',
            'type' => 'image_list',
            'title' => '启动图',
            'value' => [],
            'description' => 'App启动图',
        ],
        'share_link' => [
            'key' => 'share_link',
            'type' => 'text',
            'title' => '分享链接',
            'value' => '',
            'description' => '分享链接',
        ],
        'default_coin' => [
            'key' => 'default_coin',
            'type' => 'radio',
            'title' => '默认币种',
            'value' => 'USDT',
            'data' => [],
            'description' => '默认币种',
        ],
        'default_coin_list' => [
            'key' => 'default_coin_list',
            'type' => 'checkbox',
            'title' => '可用币种列表',
            'value' => ['USDT', 'ETH', 'BTC'],
            'data' => [],
            'description' => '全部可以用的币种',
        ],
        'ios_version' => [
            'key' => 'ios_version',
            'type' => 'text',
            'title' => '苹果版本号',
            'value' => '1.0.0',
            'description' => 'IOS Version ',
        ],
        'ios_download' => [
            'key' => 'ios_download',
            'type' => 'text',
            'title' => '苹果下载地址',
            'value' => '1.0.0',
            'description' => '正式版下载地址',
        ],
        'android_version' => [
            'key' => 'android_version',
            'type' => 'text',
            'title' => '安卓版本号',
            'value' => '1.0.0',
            'description' => 'android Version ',
        ],
        'android_download' => [
            'key' => 'android_download',
            'type' => 'text',
            'title' => '安卓下载地址',
            'value' => '1.0.0',
            'description' => '正式版下载地址',
        ],
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('core::admin.app.settings', [
            'configList' => $this->normalizeSchema(config('api::settings', [])),
        ]);
    }

    protected function normalizeSchema(array $data)
    {
        /** @var CoinService $coinService */
        $coinService = resolve(CoinService::class);
        $coins = $coinService->all();
        $coinNames = $coins->pluck('symbol');
        $this->schema['default_coin_list']['data'] = $this->schema['default_coin']['data'] = $coinNames;
        return array_map(function ($value) use ($data) {
            return array_merge($value, [
                'value' => $data[$value['key']] ?? $value['value'],
            ]);
        }, $this->schema);
    }

    /**
     * @param ConfigRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ConfigRequest $request)
    {
        store_config('api::settings', $request->post());

        return response()->redirectTo(route('admin.app.settings.index'));
    }


}
