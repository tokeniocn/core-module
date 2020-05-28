<?php

namespace Modules\Core\Http\Controllers\Admin\Api\User;

use Illuminate\Http\Request;
use Modules\Core\Services\Frontend\UserCertifyService;

class CertifyController
{

    public function index(Request $request, UserCertifyService $userCertifyService)
    {
        $where = [];
        if (!empty($request->id)) {
            $where[] = ['user_id', $request->id];
        }

        if (!empty($request->keyword)) {
            $where[] = ['name', 'like', '%' . $request->keyword . '%'];
        }

        if (!empty($request->status)) {
            $where[] = ['status', $request->status];
        }

        return $userCertifyService->all($where, [
            'paginate' => true,
            'orderBy' => ['id', 'desc'],
            'with' => ['user']
        ]);
    }

    public function update($id, Request $request, UserCertifyService $userCertifyService)
    {
        $userCertifyService->certifyVerify($id, $request->status);
        return [];
    }
}