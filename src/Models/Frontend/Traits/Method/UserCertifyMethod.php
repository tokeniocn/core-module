<?php

namespace Modules\Core\Models\Frontend\Traits\Method;

use Modules\Core\Models\Frontend\UserCertify;

trait UserCertifyMethod
{
    public function setPassed()
    {
        $this->status = UserCertify::STATUS_SUCCESS;
        return $this;
    }

    public function setReject()
    {
        $this->status = UserCertify::STATUS_REJECT;
        return $this;
    }
}