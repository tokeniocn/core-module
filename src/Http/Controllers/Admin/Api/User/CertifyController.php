<?php

namespace Modules\Core\Http\Controllers\Admin\Api\User;

use Illuminate\Http\Request;
use Modules\Core\Services\Frontend\UserCertifyService;

class CertifyController
{

    public function index(Request $request, UserCertifyService $userCertifyService)
    {
        return $userCertifyService->all([], ['paginate' => true, 'orderBy' => ['id', 'desc']]);
    }

    public function update($id, Request $request, UserCertifyService $userCertifyService)
    {
        $userCertifyService->certifyVerify($id, $request->status);
        return [];
    }
}