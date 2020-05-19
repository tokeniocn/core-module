<?php

use Modules\Core\Http\Controllers\Admin\Announce\AnnounceController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'announce',
    'as' => 'announce.'
], function () {

    Route::get('/', [AnnounceController::class, 'index'])->name('index');
    Route::get('/create', [AnnounceController::class, 'create'])->name('create');
    Route::get('/{id}', [AnnounceController::class, 'edit'])->name('edit');
    Route::post('/{id}', [AnnounceController::class, 'update'])->name('update');

});
