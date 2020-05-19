<?php

use Modules\Core\Http\Controllers\Frontend\Api\NoticeController;

Route::group([
    'prefix'     => 'v1/notice',
    'as'         => 'notice.'
], function () {
    Route::get('/', [NoticeController::class, 'index'])->name('index');
    Route::get('/{key}', [NoticeController::class, 'info'])->name('info');
});
