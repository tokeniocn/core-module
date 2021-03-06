<?php

namespace Modules\Core\Events\Admin\Auth;

use App\Models\AdminUser;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserLoggedOut.
 */
class UserLoggedOut
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct(AdminUser $user)
    {
        $this->user = $user;
    }
}
