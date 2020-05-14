<?php

namespace Modules\Core\Services\Frontend;

use Closure;
use UnexpectedValueException;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Modules\Core\Exceptions\ModelSaveException;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Services\Traits\HasQuery;
use Modules\Core\Services\Traits\HasThrottles;
use Modules\Core\Exceptions\Frontend\Auth\UserVerifyNotFundException;

class UserVerifyService
{
    const TYPE_RESET_PASSWORD = 'reset_password';
    const TYPE_CHANGE_PASSWORD = 'change_password';
    const TYPE_RESET_PAY_PASSWORD = 'reset_pay_password';
    const TYPE_CHANGE_PAY_PASSWORD = 'change_pay_password';
    const TYPE_RESET_EMAIL = 'reset_email';
    const TYPE_RESET_MOBILE = 'mobile';

    use HasQuery,
        HasThrottles;

    /**
     * @var UserVerify
     */
    protected $model;

    public function __construct(UserVerify $model)
    {
        $this->model = $model;
    }

    /**
     * @param $key
     * @param Closure|null $tokenCallback
     * @param array $options
     *
     * @return mixed|string
     */
    public function generateUniqueToken($key, Closure $tokenCallback = null, array $options = [])
    {
        $i = 1;
        $max = $options['max'] ?? 10;
        while (true) {
            $token = is_callable($tokenCallback) ? $tokenCallback() : Str::random(6);
            $verify = $this->one([
                'key' => $key,
                'token' => $token
            ], ['exception' => false]);

            if (!$verify) {
                return $token;
            } elseif ($i > $max) {
                throw new UnexpectedValueException(trans('超出唯一Token生成次数(:max)', ['max' => $max]));
            }

            $i++;
        }
    }

    /**
     * @param $user
     * @param $key
     * @param $type
     * @param null $token
     * @param null $expiredAt
     * @param array $options
     *
     * @return UserVerify
     */
    public function createByUser($user, $key, $type, $token = null, $expiredAt = null, array $options = [])
    {
        $userId = with_user_id($user);

        /** @var UserVerify $verify */
        $verify = $this->create([
            'user_id' => $userId,
            'key' => $key,
            'type' => $type,
            'token' => $token ?: $this->generateUniqueToken($key),
            'expired_at' => $expiredAt ?: Carbon::now()->addSeconds(config('core::user.verify.expires', 600)),
        ], $options);

        $deleteOther = $options['delete_other'] ?? true;
        if ($deleteOther || ($options['expire_other'] ?? true)) {
            $verify->makeOtherExpired($deleteOther);
        }

        return $verify;
    }

    /**
     * @param User $user
     */
    protected function checkResetEmailAttempts($user)
    {
        $key = with_user_id($user) . '|' . self::TYPE_RESET_EMAIL;
        $maxAttempts = config('core::system.reset.email.maxAttempts', 3);
        $decaySeconds = config('core::system.reset.email.decaySeconds', 600);
        if ($this->hasTooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'email' => [trans('请求次数太多')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }
        $this->incrementAttempts($key, $decaySeconds);
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

        $this->checkResetEmailAttempts($user);

        /** @var UserVerifyService $userVerifyService */
        $userVerifyService = resolve(UserVerifyService::class);

        /** @var UserVerify $verify */
        $verify = $userVerifyService->createByUser($user, $email, 'reset_mobile', null, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

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
    public function resetEmail($token, $email, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $email,
            'token' => $token,
            'type' => self::TYPE_RESET_EMAIL
        ], [
            'with' => ['user'],
            'exception' => function () {
                return new UserVerifyNotFundException(trans('验证失败'));
            }
        ]);

        $userVerify->user->email = $email;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param User $user
     */
    protected function checkResetMobileAttempts($user)
    {
        $key = with_user_id($user) . '|' . self::TYPE_RESET_MOBILE;
        $maxAttempts = config('core::system.reset.mobile.maxAttempts', 3);
        $decaySeconds = config('core::system.reset.mobile.decaySeconds', 600);
        if ($this->hasTooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'mobile' => [trans('请求次数太多')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }
        $this->incrementAttempts($key, $decaySeconds);
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

        $this->checkResetMobileAttempts($user);

        $token = $this->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->createByUser($user, $mobile, 'reset_mobile', $token, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

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
    public function resetMobile($token, $mobile, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $mobile,
            'token' => $token,
            'type' => self::TYPE_RESET_MOBILE
        ], [
            'with' => ['user'],
            'exception' => function () {
                return new UserVerifyNotFundException(trans('验证码验证失败'));
            }
        ]);

        $userVerify->user->mobile = $mobile;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param User $user
     * @throws ValidationException
     */
    protected function checkResetPasswordAttempts($user)
    {
        $key = with_user_id($user) . '|' . self::TYPE_RESET_PASSWORD;
        $maxAttempts = config('core::system.change.password.maxAttempts', 3);
        $decaySeconds = config('core::system.change.password.decaySeconds', 600);
        if ($this->hasTooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'mobile' => [trans('请求次数太多')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }
        $this->incrementAttempts($key, $decaySeconds);
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

        $this->checkResetPasswordAttempts($user);

        $token = $this->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->createByUser($user, $mobile, self::TYPE_RESET_PASSWORD, $token, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

        $user->sendMobileVerifyNotification($verify);

        return true;
    }

    /**
     * 通过短信重置密码
     *
     * @param $token
     * @param $mobile
     * @param array $options
     * @return bool
     * @throws ModelSaveException
     */
    public function resetPassword($token, $mobile, $password, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $mobile,
            'token' => $token,
            'type' => self::TYPE_RESET_PASSWORD
        ], array_merge([
            'with' => ['user'],
            'exception' => function () {
                return new UserVerifyNotFundException(trans('验证码错误'));
            }
        ], $options));

        $userVerify->user->password = $password;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }
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
        if (!$user->save()) {
            throw ModelSaveException::withModel($user);
        }
        return true;
    }


    /**
     * @param User $user
     * @throws ValidationException
     */
    protected function checkResetPayPasswordAttempts($user)
    {
        $key = with_user_id($user) . '|' . self::TYPE_RESET_PAY_PASSWORD;
        $maxAttempts = config('core::system.change.password.maxAttempts', 3);
        $decaySeconds = config('core::system.change.password.decaySeconds', 600);
        if ($this->hasTooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'mobile' => [trans('请求次数太多')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }
        $this->incrementAttempts($key, $decaySeconds);
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

        $this->checkResetPayPasswordAttempts($user);

        $token = $this->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->createByUser($user, $mobile, self::TYPE_RESET_PAY_PASSWORD, $token, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

        $user->sendMobileVerifyNotification($verify);

        return true;
    }

    public function resetPayPassword($user, $token, $password, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $user->mobile,
            'token' => $token,
            'type' => self::TYPE_RESET_PAY_PASSWORD
        ], array_merge([
            'with' => ['user'],
            'exception' => function () {
                return new UserVerifyNotFundException(trans('验证码错误'));
            }
        ], $options));

        $userVerify->user->pay_password = $password;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }
        $userVerify->setExpired()->save();

        return true;
    }


    public function resetPayPasswordByOldPassword($user, $oldPassword, $newPassword, array $options = [])
    {
        $user = with_user($user);
        $userService = resolve(UserService::class);
        $userService->checkPayPassword($user, $oldPassword, $options);
        $user->pay_password = $newPassword;
        if (!$user->save()) {
            throw ModelSaveException::withModel($user);
        }
        return true;
    }
}
