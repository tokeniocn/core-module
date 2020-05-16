<?php

namespace Modules\Core\Models\Frontend;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasFail;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

class UserInvitationTree extends Model
{
    use HasFail,
        HasTableName,
        DynamicRelationship;

    /**
     * @var string
     */
    protected $table = 'user_invitation_tree';
    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'data',
    ];
    /**
     * 应该转换为日期格式的属性.
     *
     * @var array
     */
    protected $dates = [
        'used_at',
        'created_at',
    ];
    /**
     * 应进行类型转换的属性
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    /**
     * @param $inviterId
     *
     * @return $this
     */
    public function recordInviterTree($inviterId)
    {
        $inviterId = with_user_id($inviterId);

        if ($this->exists) {
            $data = empty($this->data) ? [] : $this->data;
        } else {
            $inviterTree = static::where(['user_id' => $inviterId])->first();
            if ($inviterTree) { // 记录邀请人的上级关系
                $data = empty($inviterTree->data) ? [] : $inviterTree->data;
            } else {
                $data = [];
            }
        }
        $data[] = $inviterId;
        $this->data = $data;

        return $this;
    }
}
