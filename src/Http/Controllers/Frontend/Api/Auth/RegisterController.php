<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use App\Models\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\UserRegisterService;
use Modules\Core\Http\Requests\Frontend\Auth\RegisterRequest;
use Modules\Core\Http\Requests\Frontend\Auth\MobileRegisterRequest;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param UserRegisterService $userRegisterService
     *
     * @return User
     */
    public function register(RegisterRequest $request, UserRegisterService $userRegisterService)
    {
        $user = $userRegisterService->register($request->validationData());

        return $user->refresh();
    }

    public function registerByMobile(MobileRegisterRequest $request, UserRegisterService $userRegisterService)
    {
        $user = $userRegisterService->registerByMobile($request->validationData());

        return $user->refresh();
    }
}
