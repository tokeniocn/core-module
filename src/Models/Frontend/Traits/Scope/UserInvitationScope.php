<?php

namespace Modules\Core\Models\Frontend\Traits\Scope;

use Carbon\Carbon;

trait UserInvitationScope
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereNotExpired($query)
    {
        return $query->where('expired_at', '>=', Carbon::now());
    }
}
