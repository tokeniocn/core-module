<?php


namespace Modules\Core\Http\Controllers\Frontend\Api;


use Modules\Core\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return config('api::settings', []);
    }
}
