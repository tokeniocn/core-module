<?php

use Modules\Core\Http\Controllers\Frontend\Api\Auth\UserCertifyController;

Route::group([
    'prefix' => 'v1/certify',
    'as' => 'certify.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::post('/', [UserCertifyController::class, 'store'])->name('store');
    Route::get('/', [InvitationController::class, 'info'])->name('info');//获取最近一条实名认证记录
});
