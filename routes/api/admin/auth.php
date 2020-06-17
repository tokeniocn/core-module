<?php
use Modules\Core\Http\Controllers\Admin\Api\Auth\LoginController;
use Modules\Core\Http\Controllers\Admin\Api\Auth\LogoutController;
use Modules\Core\Http\Controllers\Admin\Api\Auth\RoleController;
use Modules\Core\Http\Controllers\Admin\Api\Auth\PermissionController;
use Modules\Core\Http\Controllers\Admin\Api\Auth\EditPasswordController;


// All route names are prefixed with 'admin.api.auth'.
Route::group([
    'namespace' => 'Auth',
    'as'        => 'auth.',
], function () {

    Route::group(['middleware' => 'guest'], function () {
        Route::post('v1/login', [LoginController::class, 'login'])->name('login'); // 密码登录
    });

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::post('v1/logout', [LogoutController::class, 'logout'])->name('logout'); // 退出登录

    });

    Route::group(['middleware' => 'auth:admin'], function () {
        //修改登录的管理员密码
        Route::post('v1/edit_password', [EditPasswordController::class, 'editPassword'])->name('edit_password');
    });

    Route::group([
        'prefix' => 'v1/auth',
        'middleware' => ['auth:admin']
    ], function () {
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions');

        Route::get('roles', [RoleController::class, 'index'])->name('roles');
        Route::post('roles', [RoleController::class, 'store'])->name('role.store');
        Route::group(['prefix' => 'roles/{role}'], function () {
            Route::get('/', [RoleController::class, 'withPermissions'])->name('role');
            Route::put('/', [RoleController::class, 'update'])->name('role.update');
            Route::delete('/', [RoleController::class, 'destroy'])->name('role.destroy');
        });
    });
});
