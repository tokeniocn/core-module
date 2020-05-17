<?php

use Modules\Core\Http\Controllers\Admin\Module\ModuleController;

Route::group([
    'namespace'  => 'Module',
    'as'         => 'module.',
    'prefix'     => 'modules',
    'middleware' => ['auth:admin'],
], function () {

    Route::get('/', [ModuleController::class, 'index'])->name('modules');

});
