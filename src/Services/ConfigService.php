<?php

namespace Modules\Core\Services;

use Modules\Core\Config\Models\Config;
use Modules\Core\Services\Traits\HasListConfig;

class ConfigService
{
    use HasListConfig;

    /**
     * @var Config Model
     */
    protected $model;

    public function __construct($key, Config $model)
    {
        $this->key = $key;
        $this->model = $model;
    }
}
