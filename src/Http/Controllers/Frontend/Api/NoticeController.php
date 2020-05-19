<?php


namespace Modules\Core\Http\Controllers\Frontend\Api;


use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\NoticeService;

class NoticeController extends Controller
{
    public function index(Request $request, NoticeService $noticeService)
    {
        return $noticeService->all([], ['paginate' => true]);
    }

    public function info(Request $request, NoticeService $noticeService)
    {
        return $noticeService->getByKey($request->key);
    }
}