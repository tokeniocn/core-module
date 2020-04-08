<?php

namespace Modules\Core\Services\Frontend;

use Closure;
use UnexpectedValueException;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Core\Exceptions\ModelSaveException;
use Modules\Core\Models\Auth\UserVerify;
use Modules\Core\src\Services\Traits\HasQueryOptions;
use Modules\Core\src\Exceptions\Frontend\Auth\UserVerifyNotFoundException;

class UserVerifyService
{
    use HasQueryOptions;

    /**
     * @param $where
     * @param array $options
     *
     * @return UserVerify
     * @throws UserVerifyNotFoundException
     */
    public function getUserVerify($where, array $options = [])
    {
        $verify = $this->withQueryOptions(UserVerify::where($where), $options)->first();

        if ( ! $verify && ($options['exception'] ?? true)) {
            throw new UserVerifyNotFoundException('Verify token not found');
        }

        return $verify;
    }

    /**
     * @param $key
     * @param Closure|null $tokenCallback
     *
     * @return mixed|string
     * @throws UserVerifyNotFoundException
     */
    public function generateUniqueToken($key, Closure $tokenCallback = null, array $otpions = [])
    {
        $i = 1;
        $max = $otpions['max'] ?? 10;
        while (true) {
            $token = is_callable($tokenCallback) ? $tokenCallback() : Str::random(6);
            $verify = $this->getUserVerify([
                'key' => $key,
                'token' => $token
            ], ['exception' => false]);

            if (!$verify) {
                return $token;
            } elseif ($i > $max) {
                throw new UnexpectedValueException('Max generate user verify token times.');
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
    public function create($user, $key, $type, $token = null, $expiredAt = null, array $options = [])
    {
        $user = with_user($user);

        /** @var UserVerify $verify */
        $verify = $user->verifies()->create([
            'user_id'    => $user->id,
            'key'        => $key,
            'type'       => $type,
            'token'      => $token ?: $this->generateUniqueToken($key),
            'expired_at' => $expiredAt ?: Carbon::now()->addSeconds(config('core::user.verify.expires', 600)),
        ]);

        $deleteOther = $options['delete_other'] ?? true;
        if ($deleteOther || ($options['expire_other'] ?? true)) {
            $verify->makeOtherExpired($deleteOther);
        }

        return $verify;
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
                'mobile' => 'Email must be set.'
            ]);
        }

        if ($email == $user->$email && $user->isEmailVerified()) {
            ValidationException::withMessages([
                'mobile' => 'Current email is already verified.'
            ]);
        }

        /** @var UserVerifyService $userVerifyService */
        $userVerifyService = resolve(UserVerifyService::class);

        /** @var UserVerify $verify */
        $verify = $userVerifyService->create($user, $email, 'reset_mobile', null, $options);
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
     * @throws UserVerifyNotFoundException
     */
    public function resetEmail($token, $email, array $options = [])
    {
        $userVerify = $this->getUserVerify([
            'key' => $email,
            'token' => $token,
            'type' => 'reset_email'
        ], [
            'with' => ['user']
        ]);

        $userVerify->user->email = $email;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param User|int $user
     * @param null $mobile
     * @param array $options
     *
     * @return bool
     * @throws UserVerifyNotFoundException
     */
    public function resetMobileNotification($user, $mobile = null, array $options = [])
    {
        /** @var User $user */
        $user = with_user($user);

        $mobile = $mobile ?: $user->mobile;

        if (empty($mobile)) {
            ValidationException::withMessages([
                'mobile' => 'Mobile must be set.'
            ]);
        }

        if ($mobile == $user->mobile && $user->isMobileVerified()) {
            ValidationException::withMessages([
                'mobile' => 'Current mobile is already verified.'
            ]);
        }

        $token = $this->generateUniqueToken($mobile, function() {
            return random_int(100000, 999999);
        });
        $verify = $this->create($user, $mobile, 'reset_mobile', $token, $options);
        $verify->makeOtherExpired();

        $user->sendEmailVerifyNotification($verify);

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
        $userVerify = $this->getUserVerify([
            'key' => $mobile,
            'token' => $token,
            'type' => 'reset_mobile'
        ], [
            'with' => ['user']
        ]);

        $userVerify->user->mobile = $mobile;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }

        $userVerify->setExpired()->save();

        return true;
    }
}
