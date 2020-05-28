<?php

namespace Modules\Core\Models\Frontend;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Frontend\Traits\Attribute\UserCertifyAttribute;
use Modules\Core\Models\Frontend\Traits\Method\UserCertifyMethod;
use Modules\Core\Models\Frontend\Traits\Relationship\UserCertifyRelationship;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\Models\Frontend\Traits\Method\UserVerifyMethod;
use Modules\Core\Models\Frontend\Traits\Scope\UserVerifyScope;
use Modules\Core\Models\Frontend\Traits\Relationship\UserVerifyRelationship;

class UserCertify extends Model
{
    use HasFail,
        HasTableName,
        DynamicRelationship;

    use UserCertifyAttribute,
        UserCertifyMethod,
        UserCertifyRelationship;

    const CERTIFY_TYPE_IDENTIFICATION = 'identification';
    const CERTIFY_TYPE_PASSPORT = 'passport';

    const CERTIFY_TYPE_MAP = [
        self::CERTIFY_TYPE_IDENTIFICATION => '身份证',
        self::CERTIFY_TYPE_PASSPORT => '护照'
    ];

    const STATUS_WAITING = 'waiting';
    const STATUS_SUCCESS = 'success';
    const STATUS_REJECT = 'reject';

    const STATUS_MAP = [
        self::STATUS_WAITING => '待审核',
        self::STATUS_SUCCESS => '已通过',
        self::STATUS_REJECT => '已驳回',
    ];
    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'obverse',
        'reverse',
        'certify_type',
        'name',
        'number',
        'status'
    ];


}
