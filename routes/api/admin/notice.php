<?php

use Modules\Core\Http\Controllers\Admin\Api\Notice\NoticeController;

// Notice Management
Route::group([
    'namespace' => 'Notice',
    'as' => 'notice.',
    'prefix' => 'v1/notice'
], function () {

    Route::get('/', [NoticeController::class, 'index'])->name('index');
    Route::post('/', [NoticeController::class, 'create'])->name('create');
    Route::post('/{key}', [NoticeController::class, 'update'])->name('update');
});
