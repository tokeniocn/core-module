<?php

use Modules\Core\Http\Controllers\Admin\App\SettingsController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'app',
    'as' => 'app.'
], function () {

    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/', [SettingsController::class, 'update'])->name('settings.update');

});
