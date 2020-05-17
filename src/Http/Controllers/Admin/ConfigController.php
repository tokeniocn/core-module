<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Container\Container;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Config\ConfigRequest;
use Modules\Core\Services\ConfigService;

/**
 * Class DashboardController.
 */
class ConfigController extends Controller
{

    /**
     * @param ConfigService $configService
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ConfigService $configService)
    {
        Container::getInstance()->make(ConfigService::class, ['key' => 'Core::a']);
        return view('core::admin.config', [
            'configList' => $configService->all(),
        ]);
    }

    /** 更新或新增系统设置
     *
     * @param ConfigRequest $request
     * @param ConfigService $configService
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ConfigRequest $request, ConfigService $configService)
    {

//        $configService->update()
        $configService->update($request->all());

        return response()->redirectTo(route('admin.config.index'));
    }
}
