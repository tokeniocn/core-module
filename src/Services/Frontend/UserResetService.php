<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Services\Traits\HasThrottles;

class UserResetService
{
    use HasThrottles;

    /**
     * @var UserVerifyService
     */
    protected $userVerifyService;

    public function __construct(UserVerifyService $userVerifyService)
    {
        $this->userVerifyService = $userVerifyService;
    }

    /**
     * @param $token
     * @param $email
     * @param array $options
     *
     * @return bool
     */
    public function resetEmail($email, $token, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($email, $token, UserVerify::TYPE_EMAIL_RESET, array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->email = $email;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param $mobile
     * @param $token
     * @param array $options
     *
     * @return bool
     */
    public function resetMobile($mobile, $token, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($mobile, $token, UserVerify::TYPE_MOBILE_RESET, array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->mobile = $mobile;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param $mobile
     * @param $token
     * @param array $options
     *
     * @return bool
     */
    public function resetMobileByOldMobile($mobile, $token, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($mobile, $token, UserVerify::TYPE_MOBILE_RESET, array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->mobile = $mobile;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param $mobile
     * @param $token
     * @param $password
     * @param array $options
     *
     * @return bool
     */
    public function resetPassword($mobile, $token, $password, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($mobile, $token, UserVerify::TYPE_PASSWORD_RESET, array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->password = $password;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();
        return true;
    }


    /**
     * @param $user
     * @param $data
     * @param array $options
     */
    public function resetPasswordByOldPassword($user, $oldPassword, $newPassword, array $options = [])
    {
        $user = with_user($user);
        $userService = resolve(UserService::class);
        $userService->checkPassword($user, $oldPassword, $options);
        $user->password = $newPassword;
        $user->saveIfFail();

        return true;
    }

    public function resetPayPassword($user, $token, $password, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($user->mobile, $token, UserVerify::TYPE_PAY_PASSWORD_RESET, array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->pay_password = $password;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();

        return true;
    }

    public function resetPayPasswordByOldPassword($user, $oldPassword, $newPassword, array $options = [])
    {
        $user = with_user($user);
        $userService = resolve(UserService::class);
        $userService->checkPayPassword($user, $oldPassword, $options);
        $user->pay_password = $newPassword;
        $user->saveIfFail();

        return true;
    }
}
