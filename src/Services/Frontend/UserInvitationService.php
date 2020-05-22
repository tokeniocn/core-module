<?php

namespace Modules\Core\Services\Frontend;

use Closure;
use UnexpectedValueException;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Core\Events\Frontend\UserInvited;
use Modules\Core\Models\Frontend\UserInvitation;
use Modules\Core\Models\Frontend\UserInvitationTree;
use Modules\Core\Services\Traits\HasQuery;


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
        return $this->queryCreate(array_merge($data, [
            'token' => $data['token'] ?: $this->generateUniqueToken(),
            'expired_at' => $data['expired_at'] ?: Carbon::now()->addSeconds(config('core::user.invitation.expires',
                86400 * 7)),
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
                    'mobile' => [trans('邀请码已过期')],
                ]);
            } elseif ($invitation->isUsed()) {
                throw ValidationException::withMessages([
                    'mobile' => [trans('邀请码已经被使用')],
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
                return new ModelNotFoundException(trans('邀请码未找到'));
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
            $data = array_slice($data, 0, $level);
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
        $invitationState = $options['invitation'] ?? 1;

        if ($invitationState == 0) { // 不开启邀请码
            return;
        }

        if ($invitationState == 1) { // 一码一人模式
            $invitation = $this->inviteOneUser($token, $usedUser);
        } else { // 一码多人模式
            $invitation = $this->inviteAnyUser($token, $usedUser);
        }

        return $invitation;
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

    /**获取用户的邀请码列表
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

    /**获取未使用的邀请码
     * @param $user
     * @param array $options
     * @return mixed
     */
    public function getUnusedToken($user, $options = [])
    {
        return $this->one([
            ['user_id', with_user_id($user)],
            ['expired_at', '>', date('Y-m-d H:i:s')],
        ], array_merge([
            'orderBy' => ['id', 'asc']
        ], $options));
    }
}
