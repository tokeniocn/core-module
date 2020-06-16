<?php

use Modules\Core\Http\Controllers\Frontend\Api\Auth\NotificationController;

Route::group([
    'namespace' => 'Notify',
    'as'        => 'notify.',
], function () {

    // 发送手机验证码
    Route::post('v1/notify/mobile', [NotificationController::class, 'sendMobileVerifyNotification'])
//         ->middleware(['auth:sanctum']) // TODO 登录和未登录都可以使用该路由
         ->name('mobile.send');

    // 发送邮箱验证码
    Route::post('v1/notify/email',[NotificationController::class,'sendEmailVerifyNotification'])
        ->name('email.send');
});
