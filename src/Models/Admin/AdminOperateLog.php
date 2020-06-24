<?php

namespace Modules\Core\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

class AdminOperateLog extends Model
{
    use HasFail,
        HasTableName,
        DynamicRelationship;

    /**
     * 不记录更新时间
     */
    const UPDATED_AT = false;

    /**
     * @var string
     */
    protected $table = 'admin_operate_log';

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'category',
        'operate',
        'detail',
        'log',
        'data',
        'context'
    ];

    protected $hidden = [
        'context'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'json',
        'context' => 'json',
    ];
}
