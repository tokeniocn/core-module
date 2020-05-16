<?php

namespace Modules\Core\Models\Admin;

use Modules\Core\Models\Traits\HasFail;
use Spatie\Permission\Models\Role;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Admin\Traits\Method\RoleMethod;
use Modules\Core\Models\Admin\Traits\Method\RolePermission;

class AdminRole extends Role
{
    use HasFail,
        HasTableName,
        RolePermission,
        RoleMethod;


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
                    ->where('guard_name', $this->getDefaultGuardName())
                    ->first();
    }
}
