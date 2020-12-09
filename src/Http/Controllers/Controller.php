<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 在这统一验证后台权限
     * Controller constructor.
     */
    public function __construct(Request $request)
    {

    }

    private function getPath()
    {

        $path = \Request::path(); //eg：admin/announce
        $url = \Request::url(); //eg：http://cw.laravel.cn/admin/announce
        return [
            'path' => $path,
            'url' => $url
        ];
    }

}
