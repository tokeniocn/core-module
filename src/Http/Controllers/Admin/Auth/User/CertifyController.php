<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\User;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Frontend\UserCertify;
use Modules\Core\Services\Frontend\UserCertifyService;
use Modules\Core\Services\Frontend\UserService;

class CertifyController extends Controller
{
    public function index(Request $request, UserCertifyService $userCertifyService)
    {

        return view('core::admin.certify.index', array_merge([
            'status_list' => UserCertify::STATUS_MAP,
            'certify_type' => UserCertify::CERTIFY_TYPE_MAP
        ], $request->all()));
    }
}