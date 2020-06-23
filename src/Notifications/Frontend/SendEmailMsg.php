<?php


namespace Modules\Core\Notifications\Frontend;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Notifications\Middleware\BeforeSend;

class SendEmailMsg  extends Notification implements ShouldQueue
{

    use Queueable;

    /**
     * @var UserVerify
     */
    protected $data;

    /**
     * UserEmailVerify constructor.
     *
     * @param string $email
     * @param string $token
     */
    public function __construct($data)
    {
        $this->data = $data;
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
            if (method_exists($notifiable, 'withNotificationEmail')) {
                $notifiable->withNotificationEmail($this->data->key);
            } elseif ($notifiable instanceof AnonymousNotifiable) {
                foreach ($job->channels as $channel) {
                    $notifiable->route($channel, $this->data->key);
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //return new UserVerifyEmailMessage($this->userVerify);
        /*$this->subject('邮件确认')
            ->line('您的验证码为：' . $this->userVerify->token);*/

        return (new MailMessage())
            ->greeting($this->data->key . '您好：')
            ->subject($this->data->subject)
            ->line($this->data->line);

    }

}
