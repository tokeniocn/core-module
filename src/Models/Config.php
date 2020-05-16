<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Admin\Traits\Method\ConfigMethod;

class Config extends Model
{
    use ConfigMethod;

    /**
     * @var string
     */
    protected $table = 'config';
    /**
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];

    protected $fillable = [
        'key',
        'value',
        'module',
        'remark'
    ];
}
