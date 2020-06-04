<?php

use Modules\Core\Http\Controllers\Admin\User\UserController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'users',
    'as' => 'user.'
], function () {

    Route::get('/', [UserController::class, 'index'])->name('index');

});
