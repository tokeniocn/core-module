<?php

use Modules\Core\Http\Controllers\Frontend\Api\SettingsController;

Route::group([
    'prefix' => 'v1',
], function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});
