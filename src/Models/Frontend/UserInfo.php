<?php

namespace Modules\Core\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Frontend\Traits\Relationship\UserInfoRelationship;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;

class UserInfo extends Model
{
    use HasFail,
        HasTableName,
        DynamicRelationship;

    use UserInfoRelationship;

    protected $fillable = [
        'user_id',
        'nick_name',
        'real_name',
        'age',
        'sex',
        'country'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}