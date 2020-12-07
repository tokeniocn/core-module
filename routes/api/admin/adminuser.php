<?php

use Modules\Core\Http\Controllers\Admin\Api\Auth\AdminUsersController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'admin_users',
    'as' => 'admin_users.'
], function () {

    Route::get('/index', [AdminUsersController::class, 'index'])->name('index');
    Route::post('/create', [AdminUsersController::class, 'create'])->name('create');
    Route::post('/update', [AdminUsersController::class, 'update'])->name('update');
});
