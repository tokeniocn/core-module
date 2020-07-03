<?php

namespace Modules\Core\Events\Frontend;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserInfoShow.
 */
class UserInfoShow
{
    use SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
