<?php

namespace Modules\Core\Services\Admin;


use Modules\Core\Models\Admin\OperateLog;
use Modules\Core\Services\Traits\HasQuery;

class OperateLogService
{
    use HasQuery;

    protected $model;

    public function __construct(OperateLog $model)
    {
        $this->model = $model;
    }
}
