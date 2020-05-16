<?php

namespace Modules\Core\Notifications\Frontend;

use Illuminate\Notifications\AnonymousNotifiable;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Messages\Frontend\UserVerifyMobileMessage;
use Modules\Core\Notifications\Middleware\BeforeSend;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Leonis\Notifications\EasySms\Channels\EasySmsChannel;

class UserMobileVerify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var UserVerify
     */
    protected $userVerify;

    /**
     * UserEmailVerify constructor.
     *
     * @param string $email
     * @param string $token
     */
    public function __construct(UserVerify $userVerify)
    {
        $this->userVerify = $userVerify;
    }

    public function middleware()
    {
        return [
            BeforeSend::class
        ];
    }

    public function beforeSend($job)
    {
        foreach ($job->notifiables as $notifiable) {
            if (method_exists($notifiable, 'withNotificationMobile')) {
                $notifiable->withNotificationMobile($this->userVerify->key);
            } elseif ($notifiable instanceof AnonymousNotifiable) {
                foreach ($job->channels as $channel) {
                    $notifiable->route($channel, $this->userVerify->key);
                }
            }
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [EasySmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toEasySms($notifiable)
    {
        return new UserVerifyMobileMessage($this->userVerify);
    }
}
