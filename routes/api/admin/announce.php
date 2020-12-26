<?php

use Modules\Core\Http\Controllers\Admin\Api\Announce\AnnounceController;

// Announce Management
Route::group([
    'namespace' => 'Announce',
    'as' => 'announce.',
    'prefix' => 'v1/announce'
], function () {

    Route::get('/', [AnnounceController::class, 'index'])->name('index');
    Route::post('/delete', [AnnounceController::class, 'delete'])->name('delete');
    Route::post('/', [AnnounceController::class, 'create'])->name('create');
    Route::post('/{id}', [AnnounceController::class, 'update'])->name('update');
});
