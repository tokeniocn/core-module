<?php

use Modules\Core\Http\Controllers\Admin\Auth\User\OperateLogController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'operate',
    'as' => 'operate.'
], function () {

    Route::get('/', [OperateLogController::class, 'index'])->name('index');

});
