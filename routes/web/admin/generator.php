<?php

use Modules\Core\Http\Controllers\Admin\GeneratorController;

Route::group([
    'namespace' => 'Generator',
    'as' => 'generator.',
], function () {
    Route::get('generators', [GeneratorController::class, 'index'])->name('index');
});
