<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;

class ConfigForm extends Component
{
    public $configList;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($list)
    {
        $this->configList = $list;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('core::components.config-form');
    }
}
