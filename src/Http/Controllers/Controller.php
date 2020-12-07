<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 在这统一验证后台权限
     * Controller constructor.
     */
    public function __construct()
    {
        //echo "<script>alert('没有权限');</script>";
        //throw new \Exception('权限不够');
        $this->getPath();
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
