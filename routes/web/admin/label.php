<?php

use Modules\Core\Http\Controllers\Admin\App\LabelController;

Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'label',
    'as' => 'label.'
], function () {

    Route::get('/', [LabelController::class, 'index'])->name('index');

});
