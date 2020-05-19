<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Notice;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Notice\NoticeRequest;
use Modules\Core\Services\Frontend\NoticeService;

class NoticeController extends Controller
{
    /**
     * @param Request $request
     * @param NoticeService $noticeService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, NoticeService $noticeService)
    {
        return $noticeService->all([], [
            'paginate' => true
        ]);
    }

    public function create(NoticeRequest $request, NoticeService $noticeService)
    {
        return $noticeService->store($request->all());
    }

    public function update(NoticeRequest $request, NoticeService $noticeService)
    {
        $noticeService->update($request->key, $request->all());
    }
}