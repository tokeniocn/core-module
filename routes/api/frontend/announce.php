<?php

use Modules\Core\Http\Controllers\Frontend\Api\AnnounceController;

Route::group([
    'prefix'     => 'v1/announce',
    'as'         => 'announce.'
], function () {
    Route::get('/', [AnnounceController::class, 'index'])->name('index');
    Route::get('/{key}', [AnnounceController::class, 'info'])->name('info');
});
