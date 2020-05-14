<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class ListData extends Model
{
    /**
     * @var string
     */
    protected $table = 'list_data';
    /**
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];

    protected $fillable = [
        'key',
        'value',
        'type',
        'module',
        'remark'
    ];

    public function scopeModule($query, $module)
    {
        if (is_array($module)) {
            $query->whereIn('module', $module);
        } else {
            $query->where('module', $module);
        }
    }

    public function scopeType($query, $type)
    {
        $query->where('type', $type);
    }
}
