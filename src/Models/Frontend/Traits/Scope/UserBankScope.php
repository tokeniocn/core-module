<?php


namespace Modules\Core\Models\Frontend\Traits\Scope;

/**
 * Trait UserBankScope
 * @package Modules\Core\Models\Frontend\Traits\Scope
 */
trait UserBankScope
{
    public function scopeWhereEnable($query, $enable = 1)
    {
        return $query->where('enable', $enable);
    }
}