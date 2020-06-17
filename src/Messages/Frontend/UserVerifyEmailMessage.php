<?php

namespace Modules\Core\Messages\Frontend;

use Modules\Core\Models\Frontend\UserVerify;
use Illuminate\Notifications\Messages\MailMessage;

class UserVerifyEmailMessage extends MailMessage
{
    /**
     * @var UserVerify
     */
    protected $userVerify;

    public function __construct(UserVerify $userVerify)
    {
        $this->userVerify = $userVerify;

        $this->init();
    }

    protected function init()
    {
        $this->subject('邮件确认')
            ->line('您的验证码为：' . $this->userVerify->token);
//             ->action('验证邮箱', route('frontend.auth.email.verify', ['token' => $this->userVerify->token]));
    }
}
