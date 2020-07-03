<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Illuminate\Http\Request;
use Modules\Core\Events\Frontend\UserInfoShow;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\ResetMobileByOldMobileRequest;
use Modules\Core\Services\Frontend\UserResetService;
use Modules\Core\Services\Frontend\UserVerifyService;
use Modules\Core\Http\Requests\Frontend\Auth\VerifyMobileRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\SetPayPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPayPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPasswordByOldPasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPayPasswordByOldPasswordRequest;

class UserController extends Controller
{
    /**
     * 获取用户信息
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function info(Request $request)
    {
        $user = $request->user();
        event(new UserInfoShow($user));
        $user->load('userInfo');
        return $user;
    }

    /**
     * 修改手机号码(验证手机号)
     *
     * @param VerifyMobileRequest $request
     * @param UserVerifyService $userVerifyService
     *
     * @return array
     */
    public function resetMobile(VerifyMobileRequest $request, UserResetService $userResetService)
    {
        $userResetService->resetMobile($request->mobile, $request->code);

        return [];
    }

    /**
     * 修改手机号码(通过旧手机验证码)
     *
     * @param ResetMobileByOldMobileRequest $request
     * @param UserResetService $userResetService
     *
     * @return array
     */
    public function resetMobileByOldMobile(ResetMobileByOldMobileRequest $request, UserResetService $userResetService)
    {
        $userResetService->resetMobileByOldMobile($request->new_mobile, $request->new_code, $request->code);

        return [];
    }

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
        $userResetService->resetPassword($request->mobile, $request->code, $request->password);

        return [];
    }

    /**
     * 修改密码（通过邮箱验证码）
     *
     * @param ResetPasswordRequest $request
     * @param UserResetService $userResetService
     * @return array
     */
    public function resetPasswordByEmail(ResetPasswordRequest $request, UserResetService $userResetService)
    {
        $userResetService->resetPasswordByEmail($request->email, $request->code, $request->password);
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
     * 设置交易密码
     *
     * @param SetPayPasswordRequest $request
     * @param UserResetService $userResetService
     *
     * @return array
     */
    public function setPayPassword(SetPayPasswordRequest $request, UserResetService $userResetService)
    {
        $userResetService->setPayPassword($request->user(), $request->password);

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
