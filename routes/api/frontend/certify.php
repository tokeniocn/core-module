<?php

use Modules\Core\Http\Controllers\Frontend\Api\Auth\UserCertifyController;

Route::group([
    'prefix' => 'v1/certify',
    'as' => 'certify.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::post('/', [UserCertifyController::class, 'store'])->name('store');
    Route::get('/', [UserCertifyController::class, 'info'])->name('info');

});
