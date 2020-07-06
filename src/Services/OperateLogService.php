<?php

namespace Modules\Core\Services;


use Modules\Core\Models\OperateLog;
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
