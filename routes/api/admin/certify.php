<?php

use Modules\Core\Http\Controllers\Admin\Api\User\CertifyController;

Route::group([
    'namespace' => 'Certify',
    'as' => 'certify.',
    'prefix' => 'v1/certify'
], function () {
    Route::get('/', [CertifyController::class, 'index'])->name('index');
    Route::post('/{id}', [CertifyController::class, 'update'])->name('update');
});
