<?php

use Modules\Core\Http\Controllers\Admin\Auth\LoginController;
use Modules\Core\Http\Controllers\Admin\Auth\Role\RoleController;

Route::group([
    'namespace' => 'Auth',
    'as'        => 'auth.',
], function () {

    Route::group(['middleware' => ['guest']], function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login'); // 密码登录
        Route::post('login', [LoginController::class, 'login'])->name('login.post'); // 提交密码登录
    });

    Route::group(['prefix' => 'auth', 'middleware' => ['auth:admin']], function () {

        Route::post('logout', [LoginController::class, 'logout'])->name('logout'); // 退出登录

        // Role Management
        Route::group(['namespace' => 'Role'], function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles');
            Route::get('roles/create', [RoleController::class, 'create'])->name('role.create');
            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
        });
    });
});
