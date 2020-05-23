<?php


use Modules\Core\Http\Controllers\Frontend\Api\Auth\LoginController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\RegisterController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\UserController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\LogoutController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\VerifyController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\ResetController;

/*
 * Frontend Access Controllers
 * All route names are prefixed with 'frontend.auth'.
 */
Route::group([
    'namespace' => 'Auth',
    'as' => 'auth.'
], function () {

    Route::group(['middleware' => 'guest'], function () {

        Route::post('v1/login', [LoginController::class, 'loginByGuessString'])->name('login'); // 密码登录
        Route::post('v1/mobile_login', [LoginController::class, 'loginByMobile'])->name('login.mobile'); // 手机号登录
        Route::post('v1/register', [RegisterController::class, 'register'])->name('register'); // 用户注册
        Route::get('v1/mobile_register', [RegisterController::class, 'requestRegisterByMobile'])->name('register.mobile.request'); // 手机号注册获取验证码
        Route::post('v1/mobile_register', [RegisterController::class, 'registerByMobile'])->name('register.mobile'); // 手机号注册
        Route::get('v1/reset/password', [ResetController::class, 'requestResetPassword'])->name('reset.password.request'); // 获取重置支付密码短信
        Route::post('v1/reset/password', [ResetController::class, 'resetPassword'])->name('reset.password'); // 重置登录密码(旧密码)
    });
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('v1/logout', [LogoutController::class, 'logout'])->name('logout'); // 退出登录
    });

    Route::group([
        'prefix' => 'v1/auth',
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('info', [UserController::class, 'info'])->name('info'); // 登录会员信息

        Route::post('reset/password_by_old', [ResetController::class, 'resetPasswordByOldPassword'])->name('reset.password_by_old'); // 修改登录密码(旧密码)

        Route::get('reset/pay_password', [ResetController::class, 'requestResetPayPassword'])->name('reset.pay_password.request'); // 获取重置支付密码短信
        Route::post('pay_password', [ResetController::class, 'setPayPassword'])->name('set.pay_password'); // 设置支付密码
        Route::post('reset/pay_password', [ResetController::class, 'resetPayPassword'])->name('reset.pay_password'); // 重置支付密码(短信验证码)
        Route::post('reset/pay_password_by_old', [ResetController::class, 'resetPayPasswordByOldPassword'])->name('reset.pay_password_by_old'); // 修改支付密码(旧支付密码)

        Route::get('reset/email', [ResetController::class, 'requestResetEmail'])->name('reset.email.request'); // 验证邮箱请求
        Route::get('reset/mobile', [ResetController::class, 'requestResetMobile'])->name('reset.mobile.request'); // 修改手机号请求
        Route::post('verify/email', [VerifyController::class, 'verifyEmail'])->name('verify.email'); // 修改邮箱
        Route::post('verify/mobile', [VerifyController::class, 'verifyMobile'])->name('verify.mobile'); // 修改手机号
    });
});
