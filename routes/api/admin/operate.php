<?php

use Modules\Core\Http\Controllers\Admin\Api\Operate\OperateLogController;

Route::group([
    'namespace' => 'Operate',
    'as' => 'operate.',
    'prefix' => 'v1/operate'
], function () {

    Route::get('/', [OperateLogController::class, 'index'])->name('index');
});
