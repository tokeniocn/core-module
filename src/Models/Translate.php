<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\Models\Traits\HasFail;

class Translate extends Model
{
    use HasFail,
        DynamicRelationship;
    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'params',
    ];

    protected $casts = [
        'params' => 'json'
    ];

    protected $hidden = [
        'translatable_type',
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
