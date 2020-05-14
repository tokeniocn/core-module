<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Models\ListData;
use Modules\Core\Services\Traits\HasListConfig;

class ConfigService
{
    use HasListConfig;

    /**
     * @var ListData
     */
    protected $key = 'core::config';
}
