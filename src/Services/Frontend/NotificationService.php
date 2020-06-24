<?php

namespace Modules\Core\Services\Frontend;

use Illuminate\Notifications\AnonymousNotifiable;

/*use Modules\Core\Notifications\Frontend\SystemWalletBalanceWarning;*/

use Illuminate\Notifications\Messages\MailMessage;

//use Modules\Core\Messages\EmailMessage;
use Illuminate\Notifications\Notification;
use Leonis\Notifications\EasySms\Messages\EasySmsMessage as MobileMessage;
use Modules\Core\Messages\SystemWalletBalanceEmailMessage;
use Modules\Core\Notifications\Frontend\SendMobileMsg;
use Modules\Core\Notifications\Frontend\email\SystemWalletBalanceWarning;
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
            $verify = $userVerifyService->createWithKey($mobile, $type, $token, null, $options['createOptions'] ?? []);

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
            config('core::system.notification_email_maxAttempts', 3),
            config('core::system.notification_email_decaySeconds', 600)
        );

        $userVerifyService = resolve(UserVerifyService::class);

        if ($user) {
            $user = with_user($user);
            $verify = $userVerifyService->createWithUser($user, $email, $type, null, null, $options['createOptions'] ?? []);

            $user->sendEmailVerifyNotification($verify);
        } else {
            $verify = $userVerifyService->createWithKey($email, $type, null, null, $options['createOptions'] ?? []);

            /** @var AnonymousNotifiable $notifiable */
            $notifiable = resolve(AnonymousNotifiable::class);
            $notifiable->notify(new UserEmailVerify($verify));
        }

        return true;
    }

    /**
     * @param Notification $notification
     * @param null $user
     * @param array $options
     */
    public function sendNotification(Notification $notification, $user = null, array $options = [])
    {
        if ($user) {
            $user = with_user($user);
            $user->notify($notification);
        } else {
            /** @var AnonymousNotifiable $notifiable */
            $notifiable = resolve(AnonymousNotifiable::class);
            $notifiable->notify($notification);
        }

        return true;
    }
}
