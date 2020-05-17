<?php


namespace Modules\Core\Http\Controllers\Frontend\Api;


use Illuminate\Http\Request;
use Modules\Core\Config\Repository;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\ConfigService;

class SettingsController extends Controller
{
    public function index()
    {
        return config('api::settings', []);
    }
}
