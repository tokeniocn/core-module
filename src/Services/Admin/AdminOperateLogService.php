<?php

namespace Modules\Core\Services\Admin;


use Modules\Core\Models\Admin\AdminOperateLog;
use Modules\Core\Services\Traits\HasQuery;

class AdminOperateLogService
{
    use HasQuery;

    protected $model;

    public function __construct(AdminOperateLog $model)
    {
        $this->model = $model;
    }
}
