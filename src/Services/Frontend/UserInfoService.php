<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Models\Frontend\UserInfo;
use Modules\Core\Services\Traits\HasQuery;

class UserInfoService
{
    use HasQuery;

    public function __construct(UserInfo $model)
    {
        $this->model = $model;
    }
}