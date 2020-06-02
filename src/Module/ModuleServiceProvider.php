<?php

namespace Modules\Core\Module;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Module\Traits\HasSeeds;
use Modules\Core\Module\Traits\HasModule;
use Modules\Core\Module\Traits\HasSchedule;

class ModuleServiceProvider extends ServiceProvider
{
    use HasModule,
        HasSchedule;
}
