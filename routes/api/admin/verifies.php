<?php

use Modules\Core\Http\Controllers\Admin\Api\User\VerifiesController;

Route::group([
    'namespace' => 'Verifies',
    'as' => 'verifies.',
    'prefix' => 'v1/verifies'
], function () {

    Route::get('/', [VerifiesController::class, 'index'])->name('index');
});
