<?php

namespace Modules\Core\Models\Frontend\Traits\Method;

use Hash;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Modules\Core\Exceptions\Frontend\Auth\UserAuthVerifyException;
use Modules\Core\Exceptions\Frontend\Auth\UserEmailVerifyException;
use Modules\Core\Exceptions\Frontend\Auth\UserMobileVerifyException;
use Modules\Core\Exceptions\Frontend\Auth\UserPayPasswordEmptyException;

//use Modules\Core\Models\Frontend\UserVerify;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    /**
     * @return mixed
     */
    public function canResetEmail()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canResetPassword()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canResetPayPassword()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * 是否实名认证
     *
     * @return mixed
     */
    public function isAuthVerified($exception = true)
    {
        $verified = $this->auth_verified_at != null;

        if (!$verified && $exception) {
            throw new UserAuthVerifyException(trans('未实名认证'));
        }

        return $verified;
    }

    /**
     * 是否验证邮箱
     *
     * @return bool 是否验证邮箱
     */
    public function isEmailVerified($exception = true)
    {
        $verified = $this->email_verified_at != null;

        if (!$verified && $exception) {
            throw new UserEmailVerifyException(trans('邮箱未验证'));
        }

        return $verified;
    }

    /**
     * 是否验证手机号
     *
     * @return bool 是否验证手机号
     */
    public function isMobileVerified($exception = true)
    {
        $verified = $this->mobile_verified_at != null;

        if (!$verified && $exception) {
            throw new UserMobileVerifyException(trans('手机号未验证'));
        }

        return $verified;
    }

    /**
     * 是否设置的支付密码
     *
     * @param bool $exception
     *
     * @return bool
     * @throws UserPayPasswordEmptyException
     */
    public function isPayPasswordSet($exception = true)
    {
        $set = !empty($this->pay_password);

        if (!$set && $exception) {
            throw new UserPayPasswordEmptyException(trans('未设置支付密码'));
        }

        return $set;
    }

    /**
     * @param $email
     *
     * @return $this
     */
    public function setEmailVerified($email)
    {
        $this->email = $email;
        $this->email_verified_at = Carbon::now();

        return $this;
    }

    /**
     * @param $email
     *
     * @return $this
     */
    public function setMobileVerified($mobile)
    {
        $this->mobile = $mobile;
        $this->mobile_verified_at = Carbon::now();

        return $this;
    }

    /**
     * @return $this;
     */
    public function setAuthVerified()
    {
        $this->auth = 1;
        $this->auth_verified_at = Carbon::now();

        return $this;
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function checkPassword($password)
    {
        return Hash::check($password, $this->getAuthPassword());
    }

    /**
     * @param $payPassword
     *
     * @return bool
     */
    public function checkPayPassword($payPassword)
    {
        return Hash::check($payPassword, $this->pay_password);
    }
}
