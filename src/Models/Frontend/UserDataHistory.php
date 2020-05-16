<?php

namespace Modules\Core\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

/**
 * Class PasswordHistory.
 */
class UserDataHistory extends Model
{
    use HasFail,
        HasTableName,
        DynamicRelationship;

    /**
     * 密码类型
     */
    const TYPE_PASSWORD = 'password';
    /**
     * 支付密码类型
     */
    const TYPE_PAY_PASSWORD = 'pay_password';
    /**
     * 邮箱类型
     */
    const TYPE_EMAIL = 'email';
    /**
     * 邮箱类型
     */
    const TYPE_MOBILE = 'mobile';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['data', 'type'];
}
