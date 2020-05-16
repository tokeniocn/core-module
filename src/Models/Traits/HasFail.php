<?php

namespace Modules\Core\Models\Traits;

use Modules\Core\Exceptions\ModelSaveException;

trait HasFail
{
    /**
     * @return bool
     */
    public function saveIfFail()
    {
        if ( ! $this->save()) {
            throw ModelSaveException::withModel($this);
        }

        return true;
    }
}
