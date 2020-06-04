<?php

namespace Modules\Core\Http\Controllers\Frontend\Api;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Media;
use Illuminate\Http\Request;
use MediaUploader;
use OSS\OssClient;

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

    public function uploadSettings(Request $request)
    {

        $policy = Media::aliOssPolicy();
        return [
            'policy' => $policy,
            'expired_at' => Media::aliOssSignatureExpireTime(),
            'access_key' => config('filesystems.disks.oss.access_key'),
            'url' => Media::aliOssUrl(),
            'signature' => Media::aliOssSignature($policy)
        ];
    }
}
