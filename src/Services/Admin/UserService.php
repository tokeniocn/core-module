<?php

namespace Modules\Core\Services\Admin;

use Modules\Core\Models\Frontend\User;
use Modules\Core\Services\Traits\HasQuery;

class UserService
{
    use HasQuery;

    public function __construct(User $user)
    {
        $this->model = $user;
    }
}