<?php

namespace Modules\Core\Messages;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Leonis\Notifications\EasySms\Channels\EasySmsChannel;
use Leonis\Notifications\EasySms\Messages\EasySmsMessage as MobileMessage;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Message;
use Modules\Core\Notifications\Middleware\BeforeSend;

class SystemWalletBalanceMobileMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $mobile;
    public $data;

    public function __construct($mobile, array $data = [])
    {

        $this->mobile = $mobile;
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
            if (method_exists($notifiable, 'withNotificationMobile')) {
                $notifiable->withNotificationMobile($this->mobile);
            } elseif ($notifiable instanceof AnonymousNotifiable) {
                foreach ($job->channels as $channel) {
                    $notifiable->route($channel, $this->mobile);
                }
            }
        }
    }

    /**
     * 选择对应的发送channel频道
     */
    public function via($notifiable)
    {
        return [EasySmsChannel::class];
    }


    public function toEasySms($notifiable)
    {

        $msg = "系统钱包预警"; //发送内容 （这样写肯定过不了短信风控）
        return (new Message())->setContent($msg);
    }

}
