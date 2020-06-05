<?php


namespace Modules\Core\Models\Frontend\Traits\Relationship;

use App\Models\User;

trait UserBankRelationship
{
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}