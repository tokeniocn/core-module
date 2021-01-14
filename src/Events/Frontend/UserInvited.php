<?php

namespace Modules\Core\Events\Frontend;

use Illuminate\Queue\SerializesModels;
use Modules\Core\Models\Frontend\UserInvitation;

class UserInvited
{
    use SerializesModels;

    /**
     * @var UserInvitation
     */
    public $invitation;

    /**
     * @param $user
     */
    public function __construct(UserInvitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
