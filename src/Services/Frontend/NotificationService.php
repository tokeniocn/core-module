<?php

namespace Modules\Core\Services\Frontend;

use Illuminate\Notifications\AnonymousNotifiable;
use Modules\Core\Notifications\Frontend\UserEmailVerify;
use Modules\Core\Services\Traits\HasThrottles;
use Modules\Core\Notifications\Frontend\UserMobileVerify;

class NotificationService
{
    use HasThrottles;

    /**
     * 发送短信验证码
     *
     * @param $mobile
     * @param $type
     * @param null $user
     * @param array $options
     */
    public function sendMobileVerifyNotification($mobile, $type, $user = null, array $options = [])
    {
        $key = $mobile . '|' . $type;
        $this->checkKeyAttempts(
            $key,
            config('core::system.notification_mobile_maxAttempts', 3),
            config('core::system.notification_mobile_decaySeconds', 600)
        );

        $userVerifyService = resolve(UserVerifyService::class);

        $token = $userVerifyService->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        if ($user) {
            $user = with_user($user);
            $verify = $userVerifyService->createWithUser($user, $mobile, $type, $token, null, $options['createOptions'] ?? []);

            $user->sendMobileVerifyNotification($verify);
        } else {
            $verify = $userVerifyService->createWithKey($mobile, $type, $token, null,$options['createOptions'] ?? []);

            /** @var AnonymousNotifiable $notifiable */
            $notifiable = resolve(AnonymousNotifiable::class);
            $notifiable->notify(new UserMobileVerify($verify));
        }

        return true;
    }

    /**
     * @param $mobile
     * @param $type
     * @param null $user
     * @param array $options
     *
     * @return bool
     */
    public function sendEmailVerifyNotification($email, $type, $user = null, array $options = [])
    {
        $key = $email . '|' . $type;
        $this->checkKeyAttempts(
            $key,
            config('core::system.notification._email_maxAttempts', 3),
            config('core::system.notification._email_decaySeconds', 600)
        );

        $userVerifyService = resolve(UserVerifyService::class);

        if ($user) {
            $user = with_user($user);
            $verify = $userVerifyService->createWithUser($user, $email, $type, null, null, $options['createOptions'] ?? []);

            $user->sendEmailVerifyNotification($verify);
        } else {
            $verify = $userVerifyService->createWithKey($email, $type, null, null,$options['createOptions'] ?? []);

            /** @var AnonymousNotifiable $notifiable */
            $notifiable = resolve(AnonymousNotifiable::class);
            $notifiable->notify(new UserEmailVerify($verify));
        }


        return true;
    }
}
