<?php

namespace Modules\Core\Services\Frontend;

use Illuminate\Support\Facades\DB;
use Modules\Core\Exceptions\Frontend\Auth\UserBankException;
use Modules\Core\Exceptions\ModelSaveException;
use Modules\Core\Models\Frontend\UserBank;
use Modules\Core\Services\Traits\HasQuery;

class UserBankService
{
    use HasQuery {
        all as queryAll;
    }

    public function __construct(UserBank $model)
    {
        $this->model = $model;
    }

    /**
     * @param $user
     * @param $bank
     * @param array $options
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function all($user, array $options = [])
    {
        $list = [];
        $bank_types = array_keys(UserBank::$bankTypeMap);
        foreach ($bank_types as $type) {
            $list[$type] = $this->allWithBank(
                $user, $type, ['jsonDecode' => true]
            );
        }
        return $list;
    }

    /**
     * @param $user
     * @param $bank
     * @param array $options
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function allWithBank($user, $bank, array $options = [])
    {
        $user_id = with_user_id($user);
        $list = $this->queryAll([
            'bank' => $bank,
            'user_id' => $user_id
        ]);
        if ($options['jsonDecode'] ?? false) {
            foreach ($list as $item) {
                $item->value_decode = json_decode($item->value);
            }
        }
        return $list;
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
            UserBank::where([
                'user_id' => $user_id,
                'bank' => $bank->bank,
            ])->update(['enable' => UserBank::ENABLE_CLOSE]);
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
}
