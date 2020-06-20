<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasTableName;

class Config extends Model
{
    use HasTableName;
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
        'description',
    ];
}
