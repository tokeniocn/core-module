<?php

namespace Modules\Core\Config\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

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