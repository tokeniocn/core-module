<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Config\ConfigRequest;
use Modules\Core\Models\Admin\AdminMenu;
use Modules\Core\Services\Admin\ConfigService;

/**
 * Class DashboardController.
 */
class ConfigController extends Controller
{

    /**
     * @param ConfigService $configService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ConfigService $configService)
    {
        $configList = $configService->listForAdminEdit(['key'=>$configService->key]);
        return view('core::admin.config', [
            'configList' => $configList
        ]);
    }

    /** 更新或新增系统设置
     * @param ConfigRequest $request
     * @param ConfigService $configService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ConfigRequest $request, ConfigService $configService)
    {
        $configService->setConfig($request->all());
        return response()->redirectTo(route('admin.config.index'));
    }
}
