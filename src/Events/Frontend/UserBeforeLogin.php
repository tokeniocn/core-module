<?php

namespace Modules\Core\Events\Frontend;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserBeforeLogins.
 */
class UserBeforeLogin
{
    use SerializesModels;

    /**
     * @var User
     */
    public $user;
    /**
     * @var string
     */
    public $loginType;

    /**
     * @param $user
     */
    public function __construct(User $user, $loginType)
    {
        $this->user = $user;
        $this->loginType = $loginType;
    }
}
