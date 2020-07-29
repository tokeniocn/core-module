<?php

use Modules\Core\Http\Controllers\Admin\User\VerifiesController;
Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'verifies',
    'as' => 'verifies.'
], function () {

    Route::get('/', [VerifiesController::class, 'index'])->name('index');

});
