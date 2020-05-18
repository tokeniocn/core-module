<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Models\ListData;
use Modules\Core\Services\Traits\HasListData;

class LabelService
{
    use HasListData;

    /**
     * @var ListData
     */
    protected $model;

    /**
     * @var string
     */
    protected $type = 'label';

    public function __construct(ListData $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @param array $options
     */
    public function update(array $data, array $options = [])
    {

        $columns = $this->all(['type' => $this->type])->pluck('key')->toArray();
        $columns = array_map(function ($item) {
            return str_replace('.', '_', $item);
        }, $columns);
        $data = array_intersect_key($data, array_flip($columns));
        foreach ($data as $key => $value) {
            $key = str_replace('_', '.', $key);
            $label = $this->one(['type' => $this->type, 'key' => $key]);
            $label->value = strval($value);
            $label->save();
        }

    }

    /**
     * @param $label
     * @param array $options
     */
    public function getLabelInfoByLabel($label, array $options = [])
    {
        $label = $this->getByKey($label, $options);

        return $label['value'];
    }
}
