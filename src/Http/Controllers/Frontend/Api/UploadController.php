<?php

namespace Modules\Core\Http\Controllers\Frontend\Api;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Media;
use Illuminate\Http\Request;
use MediaUploader;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $media = tap(MediaUploader::fromSource($request->file('file')), function ($uploader) use ($request) {
            $uploader->beforeSave(function ($model, $source) use ($uploader, $request) {
                $model->original_filename = str_replace(['#', '?', '\\', '/'], '-', $source->filename());
            });
        })
            ->useHashForFilename()
            ->upload();

        $request->user()->attachMedia($media, ['author']);
        return $media;
    }
}
