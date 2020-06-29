<?php

namespace Modules\Core\Http\Controllers\Admin\Api\User;

use Illuminate\Http\Request;
use Modules\Core\Services\Frontend\UserCertifyService;
use Modules\Core\Services\Frontend\UserService;

class CertifyController
{

    public function index(Request $request, UserCertifyService $userCertifyService)
    {
        $where = [];
        if (!empty($request->id)) {
            $where[] = ['id', $request->id];
        }

        if (!empty($request->keyword)) {
            $where[] = ['name', 'like', '%' . $request->keyword . '%'];
        }

        if (isset($request->status)) {
            $where[] = ['status', $request->status];
        }

        if(!empty($request->user_info)){
            $userService = resolve(UserService::class);
            $userInfo = $userService->one(null, [
                'exception' => false,
                'queryCallback' => function ($query) use ($request) {
                    $query->orWhere('username', $request['user_info'])
                        ->orWhere('mobile', $request['user_info'])
                        ->orWhere('id', $request['user_info'])
                        ->value('id');
                }
            ]);

            if (!$userInfo) {
                $userId = -1;
            } else {
                $userId = $userInfo->id;
            }
            $where[] = ['user_id', '=', $userId];
        }

        $list = $userCertifyService->all($where, [
            'paginate' => true,
            'orderBy' => ['id', 'desc'],
            'with' => ['user']
        ]);

        foreach ($list as $item) {
            $item->certify_type_text = $item->certify_type_text;
        }
        return $list;
    }

    public function update($id, Request $request, UserCertifyService $userCertifyService)
    {
        $userCertifyService->certifyVerify($id, $request->status);
        return [];
    }
}
