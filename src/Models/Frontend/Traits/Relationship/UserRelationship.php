<?php

use Modules\Core\Models\Frontend\UserCertify;

namespace Modules\Core\Models\Frontend\Traits\Relationship;

use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\One\User;
use Modules\Core\Models\Frontend\UserAuth;
use Modules\Core\Models\Frontend\UserBank;
use Modules\Core\Models\Frontend\UserCertify;
use Modules\Core\Models\Frontend\UserInfo;
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
    public function certifies()
    {
        return $this->hasMany(UserCertify::class);
    }

    /**
     * @return mixed
     */
    public function certifyPass()
    {
        return $this->hasOne(UserCertify::class, 'user_id', 'id')->wherePass();
    }

    /**
     * @return mixed
     */
    public function banks()
    {
        return $this->hasMany(UserBank::class);

    }

    /**
     * @return mixed
     */
    public function enableBanks()
    {
        return $this->hasMany(UserBank::class)->whereEnable();
    }


    /**
     * @return mixed
     */
    public function lastActiveAccessToken()
    {
        return $this->personalAccessToken()->orderBy('last_used_at', 'desc');
    }


    /**
     * @return mixed
     */
    public function personalAccessToken()
    {
        return $this->hasOne(PersonalAccessToken::class, 'tokenable_id', 'id')
            ->where('tokenable_type', 'App\Models\User');
    }


    /**
     * @return mixed
     */
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
    }
}
