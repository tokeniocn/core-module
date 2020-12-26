<?php

use Modules\Core\Http\Controllers\Admin\Api\Label\LabelController;

// Label Management
Route::group([
    'namespace' => 'Label',
    'as' => 'label.',
    'prefix' => 'v1/label'
], function () {

    //Route::post('/', [LabelController::class, 'index'])->name('update');
    Route::post('/', [LabelController::class, 'index'])->name('index');
    Route::post('/update', [LabelController::class, 'update'])->name('update');
    Route::post('/delete', [LabelController::class, 'delete'])->name('delete');
});
