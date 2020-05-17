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
        'schema' => 'json'
    ];

    protected $fillable = [
        'key',
        'value',
        'schema',
        'module',
        'description',
    ];

    public function setValueAttribute($value)
    {
        $schema = $this->schema;
        if (!empty($schema)) {

        }
        $this->attributes['value'] = $value;
    }
}
