<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Frontend\Auth\UserCertifyRequest;
use Modules\Core\Services\Frontend\UserCertifyService;

class UserCertifyController
{
    public function store(UserCertifyRequest $request, UserCertifyService $userCertifyService)
    {
        return $userCertifyService->createWithUser($request->user(), $request->all());
    }

    public function info(Request $request, UserCertifyService $userCertifyRequest)
    {
        $certify = $userCertifyRequest->one(['user_id' => $request->user()->id], [
            'with' => 'user',
            'exception' => false
        ]);
        return $certify ?? [];
    }
}