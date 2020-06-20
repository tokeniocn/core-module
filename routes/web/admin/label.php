<?php

use Modules\Core\Http\Controllers\Admin\App\LabelController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'label',
    'as' => 'label.'
], function () {

    Route::get('/', [LabelController::class, 'index'])->name('index');
    Route::get('/create', [LabelController::class, 'create'])->name('create');
    Route::post('/', [LabelController::class, 'store'])->name('store');
    Route::get('/update', [LabelController::class, 'update'])->name('update');
});
