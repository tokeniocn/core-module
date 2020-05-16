<?php

namespace Modules\Core\Services\Frontend;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Modules\Core\Services\Traits\HasThrottles;

class UserResetService
{
    const TYPE_RESET_PASSWORD = 'reset_password';
    const TYPE_CHANGE_PASSWORD = 'change_password';
    const TYPE_RESET_PAY_PASSWORD = 'reset_pay_password';
    const TYPE_CHANGE_PAY_PASSWORD = 'change_pay_password';
    const TYPE_RESET_EMAIL = 'reset_email';
    const TYPE_RESET_MOBILE = 'reset_mobile';

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
     * @param $user
     * @param null $email
     * @param array $options
     *
     * @return bool
     */
    public function resetEmailNotification($user, $email = null, array $options = [])
    {
        /** @var User $user */
        $user = with_user($user);

        $email = $email ?: $user->email;

        if (empty($email)) {
            ValidationException::withMessages([
                'mobile' => trans('邮箱必填')
            ]);
        }

        if ($email == $user->$email && $user->isEmailVerified(false)) {
            ValidationException::withMessages([
                'mobile' => trans('当前邮箱已经验证过')
            ]);
        }

        $this->checkKeyAttempts(
            with_user_id($user) . '|' . self::TYPE_RESET_EMAIL,
            config('core::system.reset.email.maxAttempts', 3),
            config('core::system.reset.email.decaySeconds', 600)
        );

        $verify = $this->userVerifyService->createWithUser($user, $email, 'reset_mobile', null, $options['createOptions'] ?? []);

        $user->sendEmailVerifyNotification($verify);

        return true;
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
        $userVerify = $this->userVerifyService->getByKeyToken($email, $token, self::TYPE_RESET_EMAIL, array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->email = $email;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param $user
     * @param null $mobile
     * @param array $options
     *
     * @return bool
     */
    public function resetMobileNotification($user, $mobile = null, array $options = [])
    {
        /** @var User $user */
        $user = with_user($user);

        $mobile = $mobile ?: $user->mobile;

        if (empty($mobile)) {
            ValidationException::withMessages([
                'mobile' => trans('手机号必填')
            ]);
        }

        if ($mobile == $user->mobile && $user->isMobileVerified(false)) {
            ValidationException::withMessages([
                'mobile' => trans('当前手机号已经验证过')
            ]);
        }

        $this->checkKeyAttempts(
            with_user_id($user) . '|' . self::TYPE_RESET_MOBILE,
            config('core::system.reset.mobile.maxAttempts', 3),
            config('core::system.reset.mobile.decaySeconds', 600)
        );

        $token = $this->userVerifyService->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->userVerifyService->createWithUser($user, $mobile, 'reset_mobile', $token, $options['createOptions'] ?? []);

        $user->sendMobileVerifyNotification($verify);

        return true;
    }

    /**
     * @param $token
     * @param $mobile
     * @param array $options
     *
     * @return bool
     * @throws UserVerifyNotFoundException
     */
    public function resetMobile($mobile, $token, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($mobile, $token, self::TYPE_RESET_MOBILE, array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->mobile = $mobile;
        $userVerify->user->saveIfFail();

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param null $mobile
     * @param array $options
     * @return bool
     * @throws ValidationException
     */
    public function resetPasswordNotification($mobile = null, array $options = [])
    {
        $userService = resolve(UserService::class);

        /** @var User $user */
        $user = $userService->one(['mobile' => $mobile]);

        $user->isMobileVerified();

        $this->checkKeyAttempts(
            with_user_id($user) . '|' . self::TYPE_RESET_PASSWORD,
            config('core::system.reset.password.maxAttempts', 3),
            config('core::system.reset.password.decaySeconds', 600)
        );

        $token = $this->userVerifyService->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->userVerifyService->createWithUser($user, $mobile, self::TYPE_RESET_PASSWORD, $token, $options['createOptions'] ?? []);

        $user->sendMobileVerifyNotification($verify);

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
        $userVerify = $this->userVerifyService->getByKeyToken($mobile, $token, self::TYPE_RESET_PASSWORD, array_merge([
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

    /**
     * @param $user
     * @param null $mobile
     * @param array $options
     * @return bool
     * @throws ValidationException
     */
    public function resetPayPasswordNotification($user, $mobile = null, array $options = [])
    {
        /** @var User $user */
        $user = with_user($user);

        $mobile = $mobile ?: $user->mobile;

        if (empty($mobile)) {
            ValidationException::withMessages([
                'mobile' => trans('手机号必填')
            ]);
        }

        $user->isMobileVerified();

        $this->checkKeyAttempts(
            with_user_id($user) . '|' . self::TYPE_RESET_PAY_PASSWORD,
            config('core::system.change.pay_password.maxAttempts', 3),
            config('core::system.change.pay_password.decaySeconds', 600)
        );

        $token = $this->userVerifyService->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->userVerifyService->createWithUser($user, $mobile, self::TYPE_RESET_PAY_PASSWORD, $token, $options['createOptions'] ?? []);

        $user->sendMobileVerifyNotification($verify);

        return true;
    }

    public function resetPayPassword($user, $token, $password, array $options = [])
    {
        $userVerify = $this->userVerifyService->getByKeyToken($user->mobile, $token, self::TYPE_RESET_PAY_PASSWORD, array_merge([
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
