<?php


namespace Modules\Core\Models\Frontend\Traits\Scope;

/**
 * Trait UserCertifyScope
 * @package Modules\Core\Models\Frontend\Traits\Scope
 */
trait UserCertifyScope
{
    public function scopeWherePass($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }
}