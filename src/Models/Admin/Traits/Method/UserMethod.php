<?php

namespace Modules\Core\Models\Admin\Traits\Method;

use Hash;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return true;//$this->hasRole('admin');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    public function canChangePassword()
    {
        return true;
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function checkPassword($password)
    {
        return Hash::check($password, $this->getAuthPassword());
    }
}
