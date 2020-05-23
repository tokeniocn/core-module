<?php

namespace Modules\Core\Models\Frontend\Traits\Attribute;

use Illuminate\Support\Facades\Hash;

/**
 * Trait UserAttribute.
 */
trait UserAttribute
{
    /**
     * @return bool
     */
    public function getPayPasswordSetAttribute()
    {
        return !empty($this->pay_password);
    }

    public function setPayPasswordAttribute($payPassword): void
    {
        $this->attributes['pay_password'] = $this->generateHashByPassword($payPassword);
    }

    /**
     * @param $password
     */
    public function setPasswordAttribute($password): void
    {
        // Note: Password Histories are logged from the \Modules\Core\Observer\User\UserObserver class
        $this->attributes['password'] = $this->generateHashByPassword($password);
    }

    protected function generateHashByPassword($password)
    {
        // If password was accidentally passed in already hashed, try not to double hash it
        if (
            (\strlen($password) === 60 && preg_match('/^\$2y\$/', $password)) ||
            (\strlen($password) === 95 && preg_match('/^\$argon2i\$/', $password))
        ) {
            $hash = $password;
        } else {
            $hash = Hash::make($password);
        }

        return $hash;
    }

    /**
     * @return string
     */
    public function getRolesLabelAttribute()
    {
        $roles = $this->getRoleNames()->toArray();

        if (\count($roles)) {
            return implode(', ', array_map(function ($item) {
                return ucwords($item);
            }, $roles));
        }

        return 'N/A';
    }

    /**
     * @return string
     */
    public function getPermissionsLabelAttribute()
    {
        $permissions = $this->getDirectPermissions()->toArray();

        if (\count($permissions)) {
            return implode(', ', array_map(function ($item) {
                return ucwords($item['name']);
            }, $permissions));
        }

        return 'N/A';
    }
}
