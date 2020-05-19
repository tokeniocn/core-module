<?php

use Modules\Core\Http\Controllers\Admin\Notice\NoticeController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'notice',
    'as' => 'notice.'
], function () {

    Route::get('/', [NoticeController::class, 'index'])->name('index');
    Route::get('/create', [NoticeController::class, 'create'])->name('create');
    Route::get('/{key}', [NoticeController::class, 'edit'])->name('edit');
    Route::post('/{key}', [NoticeController::class, 'update'])->name('update');

});
