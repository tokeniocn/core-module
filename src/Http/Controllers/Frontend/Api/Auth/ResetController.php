<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPayPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetEmailNotificationRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetMobileNotificationRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPasswordByOldPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPayPasswordByOldPasswordRequest;
use Modules\Core\Services\Frontend\UserResetService;

class ResetController extends Controller
{
    /**
     * 修改密码(通过短信验证码)
     *
     * @param ResetPasswordRequest $request
     * @param UserResetService $userResetService
     *
     * @return array
     */
    public function resetPassword(ResetPasswordRequest $request, UserResetService $userResetService)
    {
        $userResetService->resetPassword($request->mobile, $request->sms, $request->password);

        return [];
    }

    /**
     * 修改密码(通过旧密码)
     *
     * @param ResetPasswordByOldPasswordRequest $request
     * @param UserResetService $userResetService
     *
     * @return array
     */
    public function resetPasswordByOldPassword(ResetPasswordByOldPasswordRequest $request, UserResetService $userResetService)
    {
        $userResetService->resetPasswordByOldPassword($request->user(), $request->old_password, $request->password);

        return [];
    }

    /**
     * 修改交易密码(通过短信验证码)
     *
     * @param ResetPayPasswordRequest $request
     * @param UserResetService $userResetService
     *
     * @return array
     */
    public function resetPayPassword(ResetPayPasswordRequest $request, UserResetService $userResetService)
    {
        $userResetService->resetPayPassword($request->user(), $request->code, $request->password);

        return [];
    }

    /**
     * 修改交易密码(通过旧交易密码)
     *
     * @param ResetPayPasswordByOldPasswordRequest $request
     * @param UserResetService $userResetService
     *
     * @return array
     */
    public function resetPayPasswordByOldPassword(ResetPayPasswordByOldPasswordRequest $request, UserResetService $userResetService)
    {
        $userResetService->resetPayPasswordByOldPassword($request->user(), $request->old_password, $request->password);

        return [];
    }
}
