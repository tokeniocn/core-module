<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

class OperateLog extends Model
{
    use HasFail,
        HasTableName,
        DynamicRelationship;

    /**
     * 不记录更新时间
     */
    const UPDATED_AT = false;
    /**
     * 后台操作
     */
    const SCENE_ADMIN = 'admin';
    /**
     * 后台操作
     */
    const SCENE_FRONTEND = 'frontend';

    /**
     * @var string
     */
    protected $table = 'operate_log';

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'scene',
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
