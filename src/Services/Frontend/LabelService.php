<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Models\Frontend\Label;
use Modules\Core\Services\Traits\HasListData;

class LabelService
{
    use HasListData;

    /**
     * @var Label
     */
    protected $model;

    /**
     * @var string
     */
    protected $type = 'label';

    public function __construct(Label $model)
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
            $label->saveIfFail();
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

    public function store(array $data)
    {
        $label = $this->model::create([
            'key' => $data['key'],
            'value' => $data['value'],
            'remark' => $data['remark'],
            'module' => $this->module,
            'type' => $this->type
        ]);
        return $label;
    }
}
