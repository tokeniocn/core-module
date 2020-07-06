<?php

namespace Modules\Core\Models\Frontend;

use Modules\Core\Models\ListData;
use Spatie\Translatable\HasTranslations;

class Announce extends ListData
{
    use HasTranslations;

    public $translatable = ['value'];

    public function toArray()
    {
        $attributes = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->$name;
        }

        return $attributes;
    }
}
