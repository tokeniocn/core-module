<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use App\Models\User;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Frontend\UserMobileVerified;
use Modules\Core\Http\Requests\Frontend\Auth\VerifyMobileRequest;
use Modules\Core\Services\Frontend\UserVerifyService;

class VerifyController extends Controller
{

    public function verifyMobile(VerifyMobileRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->bindMobile($request->mobile, $request->code);
        return [];
    }
}
