<?php

namespace Modules\Core\Services\Frontend;

use Closure;
use UnexpectedValueException;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Services\Traits\HasQuery;
use Modules\Core\Exceptions\Frontend\Auth\UserVerifyNotFundException;

class UserVerifyService
{
    const TYPE_RESET_PASSWORD = 'reset_password';
    const TYPE_CHANGE_PASSWORD = 'change_password';
    const TYPE_RESET_PAY_PASSWORD = 'reset_pay_password';
    const TYPE_CHANGE_PAY_PASSWORD = 'change_pay_password';
    const TYPE_RESET_EMAIL = 'reset_email';
    const TYPE_RESET_MOBILE = 'reset_mobile';
    const TYPE_REGISTER_MOBILE = 'register_mobile';

    use HasQuery {
        create as queryCreate;
    }

    /**
     * @var UserVerify
     */
    protected $model;

    public function __construct(UserVerify $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data, array $options = [])
    {
        $verify = $this->queryCreate($data, $options);

        $deleteOther = $options['delete_other'] ?? true;
        if ($deleteOther || ($options['expire_other'] ?? true)) {
            $verify->makeOtherExpired($deleteOther);
        }

        return $verify;
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
     * @param $key
     * @param $token
     * @param $type
     * @param array $options
     *
     * @return mixed
     */
    public function getByKeyToken($key, $token, $type, array $options = [])
    {
        $model = $this->one([
            'key' => $key,
            'token' => $token,
            'type' => $type,
        ], array_merge([
            'exception' => function () {
                return new UserVerifyNotFundException(trans('验证码错误'));
            }
        ], $options));

        if ($model && ($options['autoSetExpired'] ?? false)) {
            $model->autoSetExpired()->save();
        }

        return $model;
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
    public function createWithUser($user, $key, $type, $token = null, $expiredAt = null, array $options = [])
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

        return $verify;
    }

    /**
     * @param $key
     * @param $type
     * @param null $token
     * @param null $expiredAt
     * @param array $options
     *
     * @return UserVerify
     */
    public function createWithKey($key, $type, $token = null, $expiredAt = null, array $options = [])
    {
        /** @var UserVerify $verify */
        $verify = $this->create([
            'user_id' => 0,
            'key' => $key,
            'type' => $type,
            'token' => $token ?: $this->generateUniqueToken($key),
            'expired_at' => $expiredAt ?: Carbon::now()->addSeconds(config('core::user.verify.expires', 600)),
        ], $options);

        return $verify;
    }
}
