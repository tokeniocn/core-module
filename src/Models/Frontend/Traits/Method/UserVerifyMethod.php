<?php

namespace Modules\Core\Models\Frontend\Traits\Method;

use Carbon\Carbon;

trait UserVerifyMethod
{
    /**
     *  make same type expired
     */
    public function makeOtherExpired($delete = true)
    {
        $query = static::where('type', $this->type)
            ->where('id', '<>', $this->id);

        if ($this->user_id) { // 指定用户
            $query->where('user_id', $this->user_id);
        } else { // 匿名
            $query->where('key', $this->key);
        }

        if ($delete) {
            return $query->delete();
        }

        return $query
            ->whereNotExpired()
            ->update([ 'expired_at' => Carbon::now() ]);
    }

    /**
     *  make same type expired
     */
    public function setExpired()
    {
        $this->expired_at = Carbon::now();

        return $this;
    }
}
