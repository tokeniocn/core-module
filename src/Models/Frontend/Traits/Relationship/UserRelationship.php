<?php

namespace Modules\Core\Models\Frontend\Traits\Relationship;

use Modules\Core\Models\Frontend\UserAuth;
use Modules\Core\Models\Frontend\UserCertify;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Models\Frontend\UserInvitation;
use Modules\Core\Models\Frontend\UserDataHistory;
use Modules\Core\Models\Frontend\UserInvitationTree;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{

    /**
     * @return mixed
     */
    public function dataHistories()
    {
        return $this->hasMany(UserDataHistory::class);
    }

    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->dataHistories()->where('type', '=', UserDataHistory::TYPE_PASSWORD);
    }

    /**
     * @return mixed
     */
    public function payPasswordHistories()
    {
        return $this->dataHistories()->where('type', '=', UserDataHistory::TYPE_PAY_PASSWORD);
    }

    /**
     * @return mixed
     */
    public function emailHistories()
    {
        return $this->dataHistories()->where('type', '=', UserDataHistory::TYPE_EMAIL);
    }

    /**
     * @return mixed
     */
    public function mobileHistories()
    {
        return $this->dataHistories()->where('type', '=', UserDataHistory::TYPE_MOBILE);
    }

    /**
     * @return mixed
     */
    public function verifies()
    {
        return $this->hasMany(UserVerify::class);
    }

    /**
     * @return mixed
     */
    public function invitations()
    {
        return $this->hasMany(UserInvitation::class);
    }

    /**
     * @return mixed
     */
    public function invitationTree()
    {
        return $this->hasOne(UserInvitationTree::class);
    }

    /**
     * @return mixed
     */
    public function userCertifies()
    {
        return $this->hasMany(UserCertify::class);
    }
}
