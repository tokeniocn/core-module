<?php

namespace Modules\Core\Services\Frontend;

use Illuminate\Support\Facades\DB;
use Modules\Core\Exceptions\Frontend\Auth\UserBankException;
use Modules\Core\Exceptions\ModelSaveException;
use Modules\Core\Models\Frontend\UserBank;
use Modules\Core\Services\Traits\HasQuery;

class UserBankService
{
    use HasQuery;

    public function __construct(UserBank $model)
    {
        $this->model = $model;
    }


    /**
     * @param $user
     * @param array|null $banks
     * @param array $options
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function allWithBanks($user, array $banks = null, array $options = [])
    {
        return $this->all(null, array_merge($options, [
            'where' => [
                'user_id' => with_user_id($user),
            ],
            'whereIn' => [
                'bank' => $banks === null ? array_keys(UserBank::$bankTypeMap) : $banks
            ]
        ]));
    }

    /**
     * @param $user
     * @param array $data
     * @param array $options
     * @return bool|\Illuminate\Database\Eloquent\Model
     * @throws ModelSaveException
     */
    public function createWithUser($user, $data = [], array $options = [])
    {
        $user_id = with_user_id($user);

        //检查是否存在相同账户类型的记录
        $hasBank = $this->has([
            'bank' => $data['bank'],
            'user_id' => $user_id
        ]);

        return $this->create(array_merge($data,
            ['user_id' => $user_id, 'enable' => $hasBank ? UserBank::ENABLE_CLOSE : UserBank::ENABLE_OPEN]
        ), $options);
    }

    /**
     * @param $user
     * @param $id
     * @return bool
     * @throws UserBankException
     * @throws \Throwable
     */
    public function enable($user, $id)
    {
        $user_id = with_user_id($user);
        $bank = $this->one([
            'user_id' => $user_id,
            'id' => $id
        ]);

        // 更改后的启用状态，取当前记录的相反值
        $enableChanged = $bank->enable == UserBank::ENABLE_CLOSE ? UserBank::ENABLE_OPEN : UserBank::ENABLE_CLOSE;

        DB::beginTransaction();
        if ($enableChanged == UserBank::ENABLE_OPEN) {
            // 设为启用，则先将其他记录设为禁用
            $all = $this->all([
                'user_id' => $user_id,
                'bank' => $bank->bank,
            ]);
            foreach ($all as $item) {
                $item->enable = UserBank::ENABLE_CLOSE;
                $item->save();
            }
        }
        $bank->enable = $enableChanged;
        $bank->save();
        Db::commit();
        return true;
    }

    /**
     * 软删除
     * @param $user
     * @param $id
     * @throws \Exception
     */
    public function delete($user, $id)
    {
        $user_id = with_user_id($user);
        $bank = $this->one([
            'user_id' => $user_id,
            'id' => $id
        ]);
        $bank->delete();
    }


    /**
     * 获取已启用的账户列表
     * @param $user
     * @param null $bank
     * @param $options
     */
    public function enableBankList($user, $bank = null, $options = [])
    {
        return $this->allWithBanks($user, $bank, [
            'queryCallback' =>
                function ($query) use ($user) {
                    $query->where('enable', UserBank::ENABLE_OPEN)
                        ->where('user_id', with_user_id($user));
                }
        ]);
    }


    /**
     * 是否有可用的银行账户
     *
     * @param $user
     * @param array $options
     * @return bool
     * @throws UserBankException
     */
    public function hasEnableBank($user, $options = [])
    {
        $has = $this->has([
            'user_id' => with_user_id($user),
            'enable' => UserBank::ENABLE_OPEN
        ], $options);
        if (empty($has)) {
            if ($options['exception']) {
                throw new UserBankException(trans('未设置收付款账户'));
            }
            return false;
        }
        return true;
    }


    /**
     * 已启用的支付方式种类
     *
     * @param $user
     * @return \Illuminate\Support\Collection
     */
    public function enableBankType($user)
    {
        return $this->query([
            'where' => [
                'user_id' => with_user_id($user),
                'enable' => UserBank::ENABLE_OPEN
            ]
        ])->pluck('bank');
    }
}
