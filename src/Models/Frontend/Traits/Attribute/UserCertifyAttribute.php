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
    public function getCertifyTypeTextAttribute()
    {
        return trans(self::CERTIFY_TYPE_MAP[$this->attributes['certify_type']]);
    }
}