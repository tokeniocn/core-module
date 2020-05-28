<?php

use Modules\Core\Http\Controllers\Frontend\Api\UploadController;

Route::group([
    'prefix' => 'v1/media',
    'as' => 'media.',
    'namespace' => 'Media',
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('upload', [UploadController::class, 'upload'])->name('upload');
});
