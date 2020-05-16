<?php

namespace Modules\Core\Models\Admin;

use Modules\Core\Models\Traits\HasFail;
use Spatie\Permission\Models\Permission;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Admin\Traits\Method\PermissionMethod;
use Modules\Core\Models\Admin\Traits\Method\RolePermission;

class AdminPermission extends Permission
{
    use HasFail,
        HasTableName,
        RolePermission,
        PermissionMethod;

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
