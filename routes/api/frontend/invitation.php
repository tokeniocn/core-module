<?php

use Modules\Core\Http\Controllers\Frontend\Api\InvitationController;

Route::group([
    'prefix' => 'v1/invitation',
    'as' => 'invitation.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::post('/', [InvitationController::class, 'store'])->name('store');
    Route::get('/list', [InvitationController::class, 'index'])->name('index');//获取所有邀请码，分页
    Route::get('/', [InvitationController::class, 'info'])->name('info');//获取一条未使用的邀请码
    Route::get('/team', [InvitationController::class, 'team'])->name('team');
});
