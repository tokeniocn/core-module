<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\NotifyMobileRequest;
use Modules\Core\Services\Frontend\NotificationService;

class NotificationController extends Controller
{
    /**
     * 发送手机验证码
     *
     * @param NotifyMobileRequest $request
     * @param NotificationService $notificationService
     *
     * @return array
     */
    public function sendMobileVerifyNotification(NotifyMobileRequest $request,  NotificationService $notificationService)
    {
        $notificationService->sendMobileVerifyNotification($request->mobile, $request->type, $request->user());

        return [];
    }

    /**
     * 发送邮箱验证码
     *
     * @param NotifyMobileRequest $request
     * @param NotificationService $notificationService
     *
     * @return array
     */
    public function sendEmailVerifyNotification(NotifyMobileRequest $request,  NotificationService $notificationService)
    {
        $notificationService->sendEmailVerifyNotification($request->email, $request->type, $request->user());

        return [];
    }
}
