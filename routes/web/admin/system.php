<?php

use Modules\Core\Http\Controllers\Admin\System\SystemController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'system',
    'as' => 'system.'
], function () {

    Route::get('/', [SystemController::class, 'index'])->name('settings.index');
    Route::post('/', [SystemController::class, 'update'])->name('settings.update');

});
