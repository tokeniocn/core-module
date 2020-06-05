<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Frontend\Auth\UserBankStoreRequest;
use Modules\Core\Services\Frontend\UserBankService;

class UserBankController
{
    /**
     * 列表
     */
    public function index(Request $request, UserBankService $userBankService)
    {
        return $userBankService->all($request->user());
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
