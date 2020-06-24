<?php

namespace Modules\Core\Messages;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemWalletBalanceEmailMessage extends MailMessage implements ShouldQueue
{
    use Queueable;

    public $email;
    public $data; //主要放置一些消息提示参数

    public function __construct($email, array $data = [])
    {
        $this->email = $email;
        $this->data = $data;
    }


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

        $notifiable->route('mail', $this->email);

        $limeMsg = env('APP_NAME') . '系统钱包余额预警。';
        if (isset($this->data['line_msg'])) {
            $limeMsg .= "详情：" . $this->data['line_msg'];
        }
        return (new MailMessage())
            ->subject($this->data['subject_msg'] ?? '系统钱包余额邮件通知')
            ->greeting($this->data['greeting_msg'] ?? '系统钱包管理者您好：')
            ->line($limeMsg);

    }


}
