<?php

namespace Modules\Core\Models\Frontend\Traits\Relationship;


use Modules\Core\Models\Frontend\BaseUser;

trait UserInfoRelationship
{

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(BaseUser::class, 'id', 'user_id');
    }
}