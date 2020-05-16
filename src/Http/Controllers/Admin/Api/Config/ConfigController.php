<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Config;

use Illuminate\Http\Request;
use Modules\Core\Config\Repository;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Config\ConfigRequest;
use Modules\Core\Services\Admin\ConfigService;

class ConfigController extends Controller
{

    /**
     * @param Request $request
     * @param ConfigService $configService
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function index(Request $request, ConfigService $configService)
    {
        return $configService->all(['key' => 'config']);
    }


}