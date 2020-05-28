<?php

namespace Modules\Core\Models\Frontend\Traits\Relationship;

use App\Models\User;

trait UserCertifyRelationship
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}