<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Exceptions\Frontend\Auth\UserCertifyException;
use Modules\Core\Models\Frontend\UserCertify;
use Modules\Core\Services\Traits\HasQuery;

class UserCertifyService
{
    use HasQuery {
        create as queryCreate;
    }

    public function __construct(UserCertify $model)
    {
        $this->model = $model;
    }


    public function createWithUser($user, $data = [], $options = [])
    {
        //检查是否有提交未审核的
        $certify = $this->one([
            'user_id' => with_user_id($user),
            'status' => UserCertify::STATUS_WAITING
        ], ['exception' => false]);

        if (!empty($certify)) {
            throw new UserCertifyException(trans('您已经的实名认证正在审核中，请勿重复提交。'));
        }

        return $this->create(array_merge([
            'user_id' => with_user_id($user),
        ], $data), $options);
    }
}