<?php

namespace Modules\Core\Services;

use Modules\Core\Config\Repository;
use Modules\Core\Models\Config;
use Modules\Core\Services\Traits\HasListConfig;

class ConfigService
{
    use HasListConfig;

    protected $key = 'core::config';

    public function __construct($key, Config $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function setConfig(array $data)
    {
        $configList = $this->listForAdminEdit(['key' => $this->key]);
        $keys = array_column($configList, 'key');
        $values = array_intersect_key($data, array_flip($keys));
        foreach ($configList as &$config) {
            $config['value'] = $values[$config['key']];
        }
        $model = $this->one(['key' => $this->key]);
        $model->value = $configList;
        $model->save();
        resolve(Repository::class)->cacheSettingsToFile();

    }

}
