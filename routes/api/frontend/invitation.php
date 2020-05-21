<?php

use Modules\Core\Http\Controllers\Frontend\Api\InvitationController;

Route::group([
    'prefix' => 'v1/invitation',
    'as' => 'invitation.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::post('/', [InvitationController::class, 'store'])->name('store');
    Route::get('/', [InvitationController::class, 'index'])->name('index');
    Route::get('/team', [InvitationController::class, 'team'])->name('team');
});
