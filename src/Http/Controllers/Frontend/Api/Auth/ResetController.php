<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPasswordByOldPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPayPasswordByOldPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetEmailRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetMobileRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPasswordRequest;
use Modules\Core\Services\Frontend\UserVerifyService;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPayPasswordRequest;

class ResetController extends Controller
{
    /**
     * 请求修改邮箱(通过邮件)
     *
     * @param ResetEmailRequest $request
     * @param UserVerifyService $userVerifyService
     *
     * @return array
     */
    public function requestResetEmail(ResetEmailRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetEmailNotification($request->user(), $request->email);

        return [];
    }

    /**
     * 请求修改手机号(通过短信验证码)
     *
     * @param ResetMobileRequest $request
     * @param UserVerifyService $userVerifyService
     *
     * @return array
     */
    public function requestResetMobile(ResetMobileRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetMobileNotification($request->user(), $request->mobile);

        return [];
    }

    /**
     * 请求修改密码(通过短信验证码)
     *
     * @param ResetMobileRequest $request
     * @param UserVerifyService $userVerifyService
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function requestResetPassword(ResetMobileRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetPasswordNotification($request->mobile);

        return [];
    }

    /**
     * 修改密码(通过短信验证码)
     *
     * @param ResetPasswordRequest $request
     * @param UserVerifyService $userVerifyService
     * @return bool
     * @throws \Modules\Core\Exceptions\ModelSaveException
     */
    public function resetPassword(ResetPasswordRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetPassword($request->sms, $request->mobile, $request->password);

        return [];
    }

    /**
     * 修改密码(通过旧密码)
     *
     * @param ResetPasswordByOldPasswordRequest $request
     * @param UserVerifyService $userVerifyService
     *
     * @return array
     * @throws \Modules\Core\Exceptions\ModelSaveException
     */
    public function resetPasswordByOldPassword(ResetPasswordByOldPasswordRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetPasswordByOldPassword($request->user(), $request->old_password, $request->password);

        return [];
    }


    /**
     * 修改交易密码请求(发送短信验证码)
     *
     * @param Request $request
     * @param UserVerifyService $userVerifyService
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function requestResetPayPassword(Request $request, UserVerifyService $userVerifyService)
    {
        $user = $request->user();
        $userVerifyService->resetPayPasswordNotification($user, $user->mobile);

        return [];
    }

    /**
     * 修改交易密码(通过短信验证码)
     *
     * @param ResetPayPasswordRequest $request
     * @param UserVerifyService $userVerifyService
     * @return array
     * @throws \Modules\Core\Exceptions\ModelSaveException
     */
    public function resetPayPassword(ResetPayPasswordRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetPayPassword($request->user(), $request->sms, $request->password);

        return [];
    }

    /**
     * 修改交易密码(通过旧交易密码)
     *
     * @param ResetPayPasswordByOldPasswordRequest $request
     * @param UserVerifyService $userVerifyService
     *
     * @return array
     */
    public function resetPayPasswordByOldPassword(ResetPayPasswordByOldPasswordRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetPayPasswordByOldPassword($request->user(), $request->old_password, $request->password);

        return [];
    }
}
