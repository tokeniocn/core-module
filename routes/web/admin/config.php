<?php

use Modules\Core\Http\Controllers\Admin\ConfigController;

// All route names are prefixed with 'admin.'.
Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'config',
    'as' => 'config.'
], function () {
    Route::get('/', [ConfigController::class, 'index'])->name('index');
    Route::post('/', [ConfigController::class, 'update'])->name('update');
});