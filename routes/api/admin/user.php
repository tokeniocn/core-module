<?php

use Modules\Core\Http\Controllers\Admin\Api\User\UserController;

// Module Management
Route::group([
    'namespace' => 'User',
    'as' => 'user.',
    'prefix' => 'v1/user'
], function () {

    Route::get('/', [UserController::class, 'index'])->name('index');
});
