<?php

namespace Modules\Core\Models\Frontend;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\Models\Frontend\Traits\Method\UserVerifyMethod;
use Modules\Core\Models\Frontend\Traits\Scope\UserVerifyScope;
use Modules\Core\Models\Frontend\Traits\Relationship\UserVerifyRelationship;

class UserVerify extends Model
{
    use HasFail,
        HasTableName,
        DynamicRelationship;

    use UserVerifyScope,
        UserVerifyMethod,
        UserVerifyRelationship;

    const TYPE_PASSWORD_RESET = 'password_reset';
    const TYPE_PAY_PASSWORD_RESET = 'pay_password_reset';
    const TYPE_MOBILE_RESET = 'mobile_reset';
    const TYPE_MOBILE_RESET_BY_OLD = 'mobile_reset_by_old';
    const TYPE_MOBILE_SET = 'mobile_set';
    const TYPE_MOBILE_LOGIN = 'mobile_login';
    const TYPE_MOBILE_REGISTER = 'mobile_register';
    const TYPE_EMAIL_REGISTER = 'email_register';
    const TYPE_EMAIL_RESET = 'email_reset';
    const TYPE_EMAIL_RESET_BY_OLD = 'email_reset_by_old';
    const TYPE_COIN_WITHDRAW = 'coin_withdraw'; //币种提现
    const TYPE_COIN_TRANSFER = 'coin_transfer'; //币种内部转账
    const UPDATED_AT = null;

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'key',
        'token',
        'type',
        'expired_at',
    ];
    /**
     * 应该转换为日期格式的属性.
     *
     * @var array
     */
    protected $dates = [
        'expired_at',
    ];

}
