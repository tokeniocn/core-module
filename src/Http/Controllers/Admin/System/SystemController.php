<?php

namespace Modules\Core\Http\Controllers\Admin\System;

use Illuminate\Http\Request;
use Modules\Coin\Services\CoinService;
use Modules\Core\Config\Repository;
use Modules\Core\Http\Controllers\Controller;

/**
 * Class SystemController
 */
class SystemController extends Controller
{

    protected $schema = [
        'register_invitation' => [
            'key' => 'register_invitation',
            'type' => 'radio',
            'title' => '邀请码使用方式',
            'value' => '0',
            'description' => '0-无需邀请码，1-一码一人，2-一码多人',
            'data' => [
                '0' => '无需邀请码',
                '1' => '一码一人',
                '2' => '一码多人'
            ]
        ],
        'notification_mobile_maxAttempts' => [
            'key' => 'notification_mobile_maxAttempts',
            'type' => 'text',
            'title' => '短信验证码最多发送次数',
            'value' => '3',
            'description' => '',
        ],
        'notification_mobile_decaySeconds' => [
            'key' => 'notification_mobile_decaySeconds',
            'type' => 'text',
            'title' => '短信验证码默认过期时间',
            'value' => '600',
            'description' => '',
        ],
        'notification_email_maxAttempts' => [
            'key' => 'notification_email_maxAttempts',
            'type' => 'text',
            'title' => '邮件验证码最多发送次数',
            'value' => '3',
            'description' => '',
        ],
        'notification_email_decaySeconds' => [
            'key' => 'notification_mobile_decaySeconds',
            'type' => 'text',
            'title' => '短信验证码默认过期时间',
            'value' => '600',
            'description' => '',
        ],
    ];


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('core::admin.system.setting', [
            'configList' => $this->normalizeSchema(config('core::system', []))
        ]);
    }


    /**
     * @param array $data
     * @return array|array[]
     */
    protected function normalizeSchema(array $data)
    {
        return array_map(function ($value) use ($data) {
            return array_merge($value, [
                'value' => $data[$value['key']] ?? $value['value'],
            ]);
        }, $this->schema);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        store_config('core::system', $request->post());

        return response()->redirectTo(route('admin.system.settings.index'));
    }


}
