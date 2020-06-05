<?php

namespace Modules\Core\Models\Admin\Traits\Scope;

use Modules\Core\Models\Admin\AdminMenu;

/**
 * Class AdminMenuScope.
 */
trait AdminMenuScope
{
    public function scopeWhereEnabled($query, $enabled = true)
    {
        return $query->where('is_show', $enabled ? AdminMenu::STATUS_ENABLED : AdminMenu::STATUS_DISABLED);
    }
}
