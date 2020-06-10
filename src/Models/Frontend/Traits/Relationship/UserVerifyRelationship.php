<?php

namespace Modules\Core\Models\Frontend\Traits\Relationship;

use App\Models\User;

trait UserVerifyRelationship
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mobileUser()
    {
        return $this->belongsTo(User::class, 'key', 'mobile')
            ->whereMobileVerified();
    }
}
