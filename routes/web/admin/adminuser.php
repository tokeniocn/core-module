<?php

use Modules\Core\Http\Controllers\Admin\Auth\User\AdminUserController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'admin_user',
    'as' => 'admin_user.'
], function () {

    Route::get('/index', [AdminUserController::class, 'index'])->name('index');
    Route::get('/create', [AdminUserController::class, 'create'])->name('create');
    Route::get('/update', [AdminUserController::class, 'update'])->name('update');
});
