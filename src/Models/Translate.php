<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'translatable_model',
        'translatable_id',
        'key',
        'params',
    ];

    protected $casts = [
        'params' => 'json'
    ];

    protected $hidden = [
        'translatable_model',
        'translatable_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function translatable()
    {
        return $this->morphTo('translatable');
    }

    public function getTransAttribute()
    {
        return $this->trans();
    }

    public function trans($locale = null)
    {
        return trans($this->key, $this->params, $locale);
    }
}
