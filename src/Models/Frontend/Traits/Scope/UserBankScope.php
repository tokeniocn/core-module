<?php


namespace Modules\Core\Models\Frontend\Traits\Scope;


trait UserBankScope
{
    public function scopeEnable($query, $enable = self::ENABLE_OPEN)
    {
        return $query->where('enable', $enable);
    }
}