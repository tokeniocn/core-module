<?php

use Modules\Core\Http\Controllers\Frontend\Api\Auth\UserBankController;

Route::group([
    'prefix' => 'v1/bank',
    'as' => 'bank.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('/', [UserBankController::class, 'index'])->name('index');
    Route::post('/save', [UserBankController::class, 'store'])->name('store');
    Route::post('/enable', [UserBankController::class, 'enable'])->name('enable');
    Route::post('/delete', [UserBankController::class, 'delete'])->name('delete');
});
