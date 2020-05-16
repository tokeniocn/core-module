<?php


namespace Modules\Core\Services\Admin;


use Modules\Core\Config\Repository;
use Modules\Core\Models\Config;
use Modules\Core\Services\Traits\HasListConfig;
use Modules\Core\Services\Traits\HasQuery;

class ConfigService
{
    use HasQuery;

    public $key = 'config';

    public function __construct(Config $model)
    {
        $this->model = $model;
    }


    /**
     * @param $where
     * @param array $options
     * @return array
     */
    public function listForAdminEdit($where, $options = [])
    {
        $data = $this->all($where, $options);
        $configList = [];
        foreach ($data as $config) {
            foreach ($config->value as $item) {
                $configList[] = $item;
            }
        }
        return $configList;
    }


    /**
     * @param array $data
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