<?php

namespace Modules\Core\Listeners\Frontend;

use Modules\Core\Models\Frontend\UserInvitation;
use Modules\Core\Models\Frontend\UserInvitationTree;

/**
 * Class UserInvitationEventListener
 */
class UserInvitationEventListener
{
    /**
     * @param UserInvitation $invitation
     */
    public function created(UserInvitation $invitation): void
    {
        if ($invitation->isDirty('used_user_id')) {
            $this->logInvitationTree($invitation);
        }
    }

    /**
     * @param UserInvitation $invitation
     */
    public function updated(UserInvitation $invitation): void
    {
        if ($invitation->isDirty('used_user_id')) {
            $this->logInvitationTree($invitation);
        }
    }

    /**
     * @param UserInvitation $invitation
     */
    protected function logInvitationTree(UserInvitation $invitation): void
    {
        /** @var UserInvitationTree $invitationTree */
        $invitationTree = $invitation->tree()->firstOrNew([], ['data' => []]);
        $invitationTree->recordInviterTree($invitation->user_id);
        $invitationTree->save();

        $invitation->invitee->inviter_id = $invitation->user_id;
        $invitation->invitee->save();
    }
}
