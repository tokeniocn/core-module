<?php

namespace Modules\Core\Models\Admin;

use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasUuid;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Admin\AdminPermission;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\Models\Admin\Traits\Method\UserMethod;
use Modules\Core\Models\Admin\Traits\Attribute\UserAttribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Plank\Mediable\Mediable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

abstract class BaseAdminUser extends Authenticatable
{
    use HasUuid,
        HasFail,
        HasTableName,
        HasRoles,
        HasApiTokens,
        Notifiable,
        Mediable,
        DynamicRelationship;

    use UserMethod,
        UserAttribute;


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $guarded = [];

    public static $activeMap = [
        0=>'禁用',
        1=>'启用'
    ];

    public function getActiveTextAttribute(){
        return self::$activeMap[$this->active] ?? '';
    }

    public function rules(){
        return $this->hasOne(AdminRole::class,'id','rules_id');
    }

    /**
     * @return AdminRole
     */
    public function getRoleClass()
    {
        if (! isset($this->roleClass)) {
            $this->roleClass = resolve(AdminRole::class);
        }

        return $this->roleClass;
    }

    /**
     * @return AdminPermission
     */
    public function getPermissionClass()
    {
        if (! isset($this->permissionClass)) {
            $this->permissionClass = resolve(AdminPermission::class);
        }

        return $this->permissionClass;
    }

    /**
     * @return string
     */
    protected function getDefaultGuardName(): string
    {
        return $this->getRoleClass()->guardName();
    }
}
