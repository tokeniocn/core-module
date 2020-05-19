<?php

use Modules\Core\Http\Controllers\Admin\Api\Announce\AnnounceController;

// Announce Management
Route::group([
    'namespace' => 'Announce',
    'as' => 'announce.',
    'prefix' => 'v1/announce'
], function () {

    Route::get('/', [AnnounceController::class, 'index'])->name('index');
    Route::post('/', [AnnounceController::class, 'create'])->name('create');
    Route::post('/{key}', [AnnounceController::class, 'update'])->name('update');
});
