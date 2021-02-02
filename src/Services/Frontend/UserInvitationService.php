<?php

namespace Modules\Core\Services\Frontend;

use Closure;
use InvalidArgumentException;
use UnexpectedValueException;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Services\Traits\HasQuery;
use Illuminate\Validation\ValidationException;
use Modules\Core\Events\Frontend\UserInvited;
use Modules\Core\Models\Frontend\UserInvitation;
use Modules\Core\Models\Frontend\UserInvitationTree;

class UserInvitationService
{
    use HasQuery {
        one as queryOne;
        getById as queryGetById;
        create as queryCreate;
    }

    /**
     * @var UserInvitation
     */
    protected $model;

    public function __construct(UserInvitation $model)
    {
        $this->model = $model;
    }

    /**
     * @param $user
     * @param null $token
     * @param null $expiredAt
     * @param array $options
     *
     * @return bool|UserInvitation
     */
    public function createWithUser($user, $token = null, $expiredAt = null, array $options = [])
    {
        return $this->create([
            'user_id' => with_user_id($user),
            'token' => $token,
            'expired_at' => $expiredAt,
        ], $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool|UserInvitation
     */
    public function create(array $data, array $options = [])
    {
        if (isset($data['expired_at'])) {
            $expiredAt = $data['expired_at'];
        } else {
            if (config('core::system.register_invitation', 0) == 2) { // 一码多人默认99年有效期
                $expiredAt = config('core::user.invitation.any_expires', 86400 * 365 * 99);
            } else {
                $expiredAt = config('core::user.invitation.one_expires', 86400 * 30); // 默认30天
            }
            $expiredAt = date('Y-m-d H:i:s', (time() + $expiredAt));
        }

        return $this->queryCreate(array_merge($data, [
            'token' => $data['token'] ?: $this->generateUniqueToken(),
            'expired_at' => $expiredAt,
        ]), $options);
    }

    /**
     * @param Closure|null $tokenCallback
     * @param array $otpions
     *
     * @return mixed|string
     * @throws ModelNotFoundException
     */
    public function generateUniqueToken(Closure $tokenCallback = null, array $options = [])
    {
        $i = 1;
        $max = $options['max'] ?? 10;
        while (true) {
            $token = is_callable($tokenCallback) ? $tokenCallback() : Str::random(6);
            $invitation = $this->getByToken($token, ['exception' => false]);

            if (!$invitation) {
                return $token;
            } elseif ($i > $max) {
                throw new UnexpectedValueException('Max generate user invitation token times.');
            }

            $i++;
        }
    }

    /**
     * @param $token
     * @param array $options
     *
     * @return UserInvitation
     */
    public function getByToken($token, array $options = [])
    {
        $available = $options['available'] ?? false;

        $invitation = $this->one(['token' => $token], array_merge([
            'orderBy' => 'created_at',
        ], $options));

        if ($available) {
            if ($invitation->isExpired()) {
                throw ValidationException::withMessages([
                    'mobile' => [trans('core::exception.邀请码已过期')],
                ]);
            } elseif ($invitation->isUsed()) {
                throw ValidationException::withMessages([
                    'mobile' => [trans('core::exception.邀请码已经被使用')],
                ]);
            }
        }

        return $invitation;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function one($where = null, array $options = [])
    {
        return $this->queryOne($where, array_merge([
            'exception' => function () {
                return new ModelNotFoundException(trans('core::exception.邀请码未找到'));
            },
        ], $options));
    }

    /**
     * 获取用户邀请人的上级邀请树用户
     *
     * @param User|int $user
     * @param array $options 上级邀请代数, 比如: 2=只返回2代邀请用户数据
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInvitersByUser($user, array $options = [])
    {
        $user = with_user($user);

        $data = $user->invitationTree ? $user->invitationTree->data : [];
        $level = $options['level'] ?? false;

        if ($level > 0) { // 取出指定代数数据
            $data = array_slice($data, $level);
        }

        // 附带当前用户
        if ($options['attachUser'] ?? false) {
            $data[] = $user->id;
        }

        /** @var UserService $userService */
        $userService = resolve(UserService::class);

        return $userService->all(function ($query) use ($data) {
            $query->whereIn('id', $data);
        }, $options['allOptions'] ?? []);
    }

    /**
     * 获取用户下级邀请用户
     * 因为下级用户获取有性能压力 所以只能通过level来获取指定邀请代数数据
     * 建议: 如果数据太多 可以通过$options['allOptions']['paginate'] = 1 来分页获取
     *
     * @param $user
     * @param array $options
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getInviteesByUser($user, array $options = [])
    {
        $userId = with_user_id($user);

        $level = intval($options['level'] ?? 1); // 默认获取下一代

        if ($level <= 0) { // 0 也代表获取1代
            $level = 1;
        }
        // TODO 全部查询性能问题?  增加代数缓存?
        $invitationTrees = UserInvitationTree::whereJsonContains('data', $userId)->get();

        $data = [];
        foreach ($invitationTrees as $tree) {
            $treeData = array_merge($tree->data, [$tree->user_id]); // 加上tree的邀请用户算一代
            $index = array_search($userId, $treeData) + $level;
            if (array_key_exists($index, $treeData)) {
                $data[] = $treeData[$index];
            }
        }

        /** @var UserService $userService */
        $userService = resolve(UserService::class);

        return $userService->all(function ($query) use ($data) {
            $query->whereIn('id', $data);
        }, $options['allOptions'] ?? []);
    }


    /**
     * @param array $data
     * @param User $usedUser
     *
     * @return UserInvitation
     */
    public function inviteUser($token, User $usedUser, array $options = [])
    {
        // 邀请类型
        $invitation = $options['invitation'] ?? config('core::system.register_invitation', 0);

        if ($invitation == 0) { // 不开启邀请码
            return;
        }

        if (empty($token)) {
            // 强制邀请
            $mandatoryInvitation = $options['invitationMandatory'] ?? config('core::system.register_invitation_mandatory', 0);

            if ($mandatoryInvitation) {
                throw new InvalidArgumentException(trans('core::exception.请输入邀请码'));
            }

            return;
        }

        if ($invitation == 1) {
            return $this->inviteOneUser($token, $usedUser); // 一码一人模式;
        } elseif ($invitation == 2) {
            return $this->inviteAnyUser($token, $usedUser); // 一码多人模式
        }
    }

    /**
     * 一码多人模式
     *
     * @param $token
     * @param $user
     *
     * @return UserInvitation
     */
    protected function inviteOneUser($token, $usedUser)
    {
        $usedUser = with_user($usedUser);

        $invitation = $this->getByToken($token, ['available' => true]);

        $invitation->setUsed($usedUser)
            ->saveIfFail();

        event(new UserInvited($invitation));

        return $invitation;
    }

    /**
     * 一码多人模式
     *
     * @param $token
     * @param Closure $userResolver
     */
    protected function inviteAnyUser($token, $usedUser)
    {
        $usedUser = with_user($usedUser);

        $invitation = $this->getByToken($token, ['available' => true]);

        $usedInvitation = $invitation->replicate();
        $usedInvitation->setUsed($usedUser)
            ->saveIfFail();

        event(new UserInvited($usedInvitation));

        return $usedInvitation;
    }

    /**
     * 获取用户的邀请码列表
     *
     * @param $userId
     * @param array $options
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllByUser($user, $options = [])
    {
        return $this->all([
            'user_id' => with_user_id($user)
        ], array_merge([
            'orderBy' => ['id', 'desc']
        ], $options));
    }

    /**
     * 获取未使用的邀请码
     *
     * @param $user
     * @param array $options
     * @return mixed
     */
    public function getUnusedToken($user, $options = [])
    {
        return $this->one([
            ['user_id', with_user_id($user)],
            ['used_user_id', 0]
        ], array_merge([
            'orderBy' => ['id', 'asc']
        ], $options));
    }
}
