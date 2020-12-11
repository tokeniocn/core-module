<?php

namespace Modules\Core\Messages\Frontend;

use Modules\Core\Models\Frontend\UserVerify;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;

class UserVerifyMobileMessage extends Message
{
    /**
     * @var UserVerify
     */
    protected $userVerify;

    public function __construct(UserVerify $userVerify)
    {
        $this->userVerify = $userVerify;
    }

    // 定义直接使用内容发送平台的内容
    public function getContent(GatewayInterface $gateway = null)
    {
        $sign = env('SMS_SIGN','');
        if (empty($sign)) {
            $sign = env('APP_NAME', '');
        }
        return strtr('【'.$sign.'】您正在验证手机号 验证码为: [code]', $this->getData());
    }

    // 模板参数
    public function getData(GatewayInterface $gateway = null)
    {
        return [
            '[code]' => $this->userVerify->token
        ];
    }
}
