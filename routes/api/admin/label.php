<?php

use Modules\Core\Http\Controllers\Admin\Api\Label\LabelController;

// Label Management
Route::group([
    'namespace' => 'Label',
    'as' => 'label.',
    'prefix' => 'v1/label'
], function () {

    Route::post('/', [LabelController::class, 'index'])->name('update');
});
