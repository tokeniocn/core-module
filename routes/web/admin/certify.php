<?php

use Modules\Core\Http\Controllers\Admin\Auth\User\CertifyController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'certify',
    'as' => 'certify.'
], function () {
    Route::get('/', [CertifyController::class, 'index'])->name('index');
});
