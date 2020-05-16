<?php

namespace Modules\Core\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\Models\Admin\Traits\Scope\AdminMenuScope;

class AdminMenu extends Model
{
    use HasFail,
        HasTableName,
        DynamicRelationship;

    use AdminMenuScope;

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
}
