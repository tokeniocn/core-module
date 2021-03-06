<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Frontend\Auth\UserBankStoreRequest;
use Modules\Core\Models\Frontend\UserBank;
use Modules\Core\Services\Frontend\UserBankService;

class UserBankController
{
    /**
     * 列表
     */
    public function index(Request $request, UserBankService $userBankService)
    {
        $bankList = $userBankService->allWithBanks($request->user());
        $result = array_fill_keys(array_keys(UserBank::$bankTypeMap), []);
        foreach ($bankList as $bank) {
            $result[$bank->bank][] = $bank;
        }

        return $result;
    }

    /**
     * 创建
     */
    public function store(UserBankStoreRequest $request, UserBankService $userBankService)
    {
        return $userBankService->createWithUser($request->user(), $request->validationData());
    }

    /**
     * 启用/禁用
     */
    public function enable(Request $request, UserBankService $userBankService)
    {
        return $userBankService->enable($request->user(), $request->input('id', 0));
    }

    /**
     * 删除
     */
    public function delete(Request $request, UserBankService $userBankService)
    {
        return $userBankService->delete($request->user(), $request->input('id', 0));
    }
}
