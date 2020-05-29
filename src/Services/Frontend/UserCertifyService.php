<?php

namespace Modules\Core\Services\Frontend;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $user = with_user($user);
        if ($user->isAuthVerified(false)) {
            throw new UserCertifyException(trans('您已经通过实名认证，请勿重复提交。'));
        }
        //检查是否有提交未审核的
        $certify = $this->one([
            'user_id' => with_user_id($user),
            'status' => UserCertify::STATUS_WAITING
        ], ['exception' => false]);


        if (!empty($certify)) {
            throw new UserCertifyException(trans('您已经的实名认证正在审核中，请勿重复提交。'));
        }

        $certify = $this->create(array_merge($data, [
            'status' => UserCertify::STATUS_WAITING,
            'user_id' => with_user_id($user),
        ]), $options);
        $certify->status_text = $certify->statusText;
        $certify->status = intval($certify->status);
        return $certify;
    }

    public function certifyVerify($id, $status, $options = [])
    {
        $certify = $this->one(['id' => $id]);
        DB::beginTransaction();
        if ($status === UserCertify::STATUS_SUCCESS) {
            $certify->setPassed();
            $certify->user->setAuthVerified();
        } else if ($status == UserCertify::STATUS_WAITING) {
            $certify->setWaiting();
            $certify->user->setAuthVerifyFail();
        }else if ($status == UserCertify::STATUS_REJECT) {
            $certify->setReject();
            $certify->user->setAuthVerifyFail();
        }
        $certify->push();
        DB::commit();
        return true;
    }
}