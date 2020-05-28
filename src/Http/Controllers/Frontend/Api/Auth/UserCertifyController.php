<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Http\Requests\Frontend\Auth\UserCertifyRequest;
use Modules\Core\Services\Frontend\UserCertifyService;

class UserCertifyController
{
    public function store(UserCertifyRequest $request, UserCertifyService $userCertifyService)
    {
        return $userCertifyService->createWithUser($request->user(), $request->all());
    }
}