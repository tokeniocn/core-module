<?php

namespace Modules\Core\Services\Frontend;

use Illuminate\Validation\ValidationException;
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
    public function resetMobileByOldMobile($newMobile, $newMobileToken, $token, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($newMobile, $newMobileToken, UserVerify::TYPE_MOBILE_RESET, array_merge([
            'with' => ['user'],
        ], $options));

        $user = $userVerify->user;

        $userVerifyOld = $this->userVerifyService->getByKeyToken($user->mobile, $token, UserVerify::TYPE_MOBILE_RESET_BY_OLD, $options);

        $userVerify->user->mobile = $newMobile;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();
        $userVerifyOld->setExpired()->save();

        return true;
    }

    /**
     * @param $newEmail
     * @param $newEmailToken
     * @param $token
     * @param array $options
     */
    public function resetEmailByOldEmail($newEmail, $newEmailToken, $token, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($newEmail, $newEmailToken, UserVerify::TYPE_EMAIL_RESET, array_merge([
            'with' => ['user'],
        ], $options));

        $user = $userVerify->user;

        $userVerifyOld = $this->userVerifyService->getByKeyToken($user->email, $token, UserVerify::TYPE_EMAIL_RESET_BY_OLD, $options);

        $userVerify->user->email = $newEmail;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();
        $userVerifyOld->setExpired()->save();

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
            'with' => ['mobileUser'],
        ], $options));

        $userVerify->mobileUser->password = $password;
        $userVerify->mobileUser->saveIfFail();

        $userVerify->setExpired()->save();
        return true;
    }

    /**
     * @param $email
     * @param $token
     * @param $password
     * @param array $options
     * @return bool
     */
    public function resetPasswordByEmail($email, $token, $password, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($email, $token, UserVerify::TYPE_PASSWORD_RESET, array_merge([
            'with' => ['emailUser'],
        ], $options));

        $userVerify->emailUser->password = $password;
        $userVerify->emailUser->saveIfFail();

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

    public function resetPayPasswordByEmail($user, $token, $password, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($user->email, $token, UserVerify::TYPE_PAY_PASSWORD_RESET, array_merge([
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

    /**
     * 设置默认交易密码
     *
     * @param $user
     * @param $password
     * @param array $options
     */
    public function setPayPassword($user, $password, array $options = [])
    {
        $user = with_user($user);

        if ($user->isPayPasswordSet(false)) {
            throw ValidationException::withMessages([
                'password' => [trans('core::exception.支付密码已设置')]
            ]);
        }

        $user->pay_password = $password;
        $user->saveIfFail();

        return true;
    }
}
