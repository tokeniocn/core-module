<?php

namespace Modules\Core\Models;


use Carbon\Carbon;

class Media extends \Plank\Mediable\Media
{
    protected $appends = [
        'path',
        'url',
        'basename',
        'original_basename',
    ];

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    public function getPathAttribute()
    {
        return str_replace(config('app.url'), '', $this->getUrl());
    }

    public function getOriginalBasenameAttribute(): string
    {
        return $this->original_filename . '.' . $this->extension;
    }


    /**
     * @return false|string
     */
    public static function aliOssSignatureExpireTime()
    {
        $timestamp = strtotime(config('core::system.upload_signature_expire', 5) . ' minutes');
        return Carbon::createFromTimestamp($timestamp)->toISOString();

    }

    /**
     * @return string
     */
    public static function aliOssPolicy()
    {
        $policy = [
            "expiration" => self::aliOssSignatureExpireTime(), //设置该Policy的失效时间，超过这个失效时间之后，就没有办法通过这个policy上传文件了
            "conditions" => [
                ["content-length-range", 0, config('core::system.upload_max_size', 10) * 1024 * 1024] // 设置上传文件的大小限制
            ]
        ];
        return base64_encode(json_encode($policy));;
    }

    /**
     * @return string
     */
    public static function aliOssUrl()
    {
        $http = config('core::system.upload_https', 0) ? "https://" : "http://";
        return $http . config('filesystems.disks.oss.bucket') . '.' . config('filesystems.disks.oss.endpoint');
    }

    /**
     * 获取阿里云OSS客户端直传签名参数
     * @param string $policy
     * @return string
     */
    public static function aliOssSignature(string $policy = '')
    {
        return base64_encode(hash_hmac('sha1', $policy, config('filesystems.disks.oss.secret_key'), true));
    }


}
