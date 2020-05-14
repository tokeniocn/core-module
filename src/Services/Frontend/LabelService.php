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
     * @param $label
     * @param array $options
     */
    public function getLabelInfoByLabel($label, array $options = [])
    {
        $label = $this->getByKey($label, $options);

        return $label['value'];
    }
}
