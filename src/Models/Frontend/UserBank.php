<?php

namespace Modules\Core\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

class UserBank extends Model
{
    use HasFail,
        HasTableName,
        SoftDeletes,
        DynamicRelationship;

    const BANK_TYPE_WECHAT = 'wechat';
    const BANK_TYPE_ALIPAY = 'alipay';
    const BANK_TYPE_BANK = 'bank';

    public static $bankTypeMap = [
        self::BANK_TYPE_WECHAT => '微信',
        self::BANK_TYPE_ALIPAY => '支付宝',
        self::BANK_TYPE_BANK => '银行卡'
    ];

    const ENABLE_OPEN = 1;
    const ENABLE_CLOSE = 0;
    public static $enableMap = [
        self::ENABLE_CLOSE => '禁用',
        self::ENABLE_OPEN => '启用'
    ];

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'bank',
        'value',
        'enable'
    ];
}
