<?php


namespace Modules\Core\Http\Controllers\Frontend\Api;


use Illuminate\Http\Request;
use Modules\Core\Config\Repository;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\ConfigService;

class ConfigController extends Controller
{
    public function index(ConfigService $configService)
    {
        return $configService->all();
    }

    public function info(Request $request, ConfigService $configService)
    {
        $data = $configService->getByKey($request->key);
        return [$request->key => $data];
    }
}