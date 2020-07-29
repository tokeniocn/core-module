<?php


namespace Modules\Core\Http\Controllers\Admin\Api\User;


use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\UserVerifyService;

class VerifiesController extends Controller
{
    public function index(Request $request, UserVerifyService $userVerifyService)
    {
        $result = $userVerifyService->all([], [
            'orderBy' => ['id', 'desc'],
            'paginate' => true,
            'whereNotExpired' => false,
            'exception' => false
        ]);

        return $result;
    }
}
