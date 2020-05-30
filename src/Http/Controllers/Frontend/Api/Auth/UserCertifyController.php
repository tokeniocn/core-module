<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Frontend\Auth\UserCertifyRequest;
use Modules\Core\Services\Frontend\UserCertifyService;

class UserCertifyController
{
    /**
     * @param UserCertifyRequest $request
     * @param UserCertifyService $userCertifyService
     * @return bool|\Illuminate\Database\Eloquent\Model
     * @throws \Modules\Core\Exceptions\Frontend\Auth\UserAuthVerifyException
     * @throws \Modules\Core\Exceptions\Frontend\Auth\UserCertifyException
     * @throws \Modules\Core\Exceptions\ModelSaveException
     */
    public function store(UserCertifyRequest $request, UserCertifyService $userCertifyService)
    {
        return $userCertifyService->createWithUser($request->user(), $request->validationData());
    }


    /**
     * @param Request $request
     * @param UserCertifyService $userCertifyRequest
     * @return array|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function info(Request $request, UserCertifyService $userCertifyService)
    {
        $certify = $userCertifyService->getByUser($request->user(), [
            'hidden_number' => true,
            'with' => 'user',
            'exception' => false
        ]);
        return $certify ?? [];
    }
}