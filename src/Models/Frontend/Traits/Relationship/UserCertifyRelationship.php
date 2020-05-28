<?php

namespace Modules\Core\Models\Frontend\Traits\Relationship;

use Modules\TianYuan\Models\User;

trait UserCertifyRelationship
{
    public function user()
    {
        $this->belongsTo(User::class);
    }
}