<?php

namespace Modules\Core\Models\Frontend\Traits\Attribute;

trait UserCertifyAttribute
{
    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getStatusTextAttribute()
    {
        return trans(self::STATUS_MAP[$this->attributes['status']]);
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getAuthTypeTextAttribute()
    {
        return trans(self::AUTH_TYPE_MAP[$this->attributes['auth_type']]);
    }
}