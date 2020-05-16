<?php

namespace Modules\Core\Services\Frontend;

use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Notifications\AnonymousNotifiable;
use Modules\Core\Events\Frontend\UserRegistered;
use Modules\Core\Notifications\Frontend\UserMobileVerify;
use Modules\Core\Services\Traits\HasThrottles;
use PascalDeVink\ShortUuid\ShortUuid;

class UserRegisterService
{
    const TYPE_MOBILE_REGISTER = 'mobile_register';

    use HasThrottles;


    /**
     * 用户注册
     *
     * @param array $data
     * @param array $options
     *
     * @return User
     */
    public function register(array $data, array $options = [])
    {
        /** @var User $user */
        $user = DB::transaction(function() use ($data, $options) {
            /** @var UserService $userService */
            $userService = resolve(UserService::class);

            /** @var User $user */
            $user = $userService->create([
                'username' => $data['username'],
                'password' => $data['password'],
                'mobile' => $data['mobile'] ?? '',
                'email' => $data['email'] ?? ''
            ], $options['createOptions'] ?? []);

            /** @var UserInvitationService $invitationService */
            $invitationService = resolve(UserInvitationService::class);
            $invitationService->inviteUser($data['invite_code'] ?? null, $user, array_merge([
                'invitation' => config('core::system.register.invitation', 0)
            ], $options['inviteOptions'] ?? []));

            return $user;
        });

        event(new UserRegistered($user));

        return $user;
    }

    /**
     * @param $user
     * @param null $mobile
     * @param array $options
     *
     * @return bool
     */
    public function registerByMobileNotification($mobile, array $options = [])
    {
        $this->checkKeyAttempts(
            $mobile . '|' . self::TYPE_MOBILE_REGISTER,
            config('core::system.register.mobile.maxAttempts', 3),
            config('core::system.register.mobile.decaySeconds', 600)
        );
        $userVerifyService = resolve(UserVerifyService::class);

        $token = $userVerifyService->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $userVerifyService->createWithKey($mobile, static::TYPE_MOBILE_REGISTER, $token, $options['createOptions'] ?? []);

        /** @var AnonymousNotifiable $notifiable */
        $notifiable = resolve(AnonymousNotifiable::class);
        $notifiable->notify(new UserMobileVerify($verify));

        return true;
    }

    /**
     * 用户注册(手机号方式)
     *
     * @param array $data
     * @param array $options
     *
     * @return User
     */
    public function registerByMobile(array $data, array $options = [])
    {
        /** @var UserVerifyService $userService */
        $userService = resolve(UserVerifyService::class);
        $userVerify = $userService->getByKeyToken($data['mobile'], $data['code'], self::TYPE_MOBILE_REGISTER, array_merge([
            'with' => ['user'],
        ], $options));

        /** @var User $user */
        $user = DB::transaction(function() use ($data, $options) {
            /** @var UserService $userService */
            $userService = resolve(UserService::class);

            /** @var User $user */
            $user = $userService->create([
                'username' => $data['username'] ?? ShortUuid::uuid1(),
                'password' => $data['password'],
                'mobile' => $data['mobile'],
                'email' => $data['email'] ?? ''
            ], array_merge([
                'beforeSave' => function($model) {
                    /** @var User $model */
                    $model->setMobileVerified($model->mobile); // 标记为邮箱已验证
                }
            ], $options['createOptions'] ?? []));

            /** @var UserInvitationService $invitationService */
            $invitationService = resolve(UserInvitationService::class);
            $invitationService->inviteUser($data['invite_code'] ?? null, $user, array_merge([
                'invitation' => config('core::system.register.invitation', 0)
            ], $options['inviteOptions'] ?? []));

            return $user;
        });

        event(new UserRegistered($user));

        $userVerify->setExpired()->save();

        return $user;
    }
}
