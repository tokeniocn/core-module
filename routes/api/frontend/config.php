<?php

use Modules\Core\Http\Controllers\Frontend\Api\ConfigController;

Route::group([
    'prefix' => 'v1/config',
    'as' => 'config.'
], function () {
    Route::get('/{key}', [ConfigController::class, 'info'])->name('info');
    Route::get('/', [ConfigController::class, 'index'])->name('index');
});
