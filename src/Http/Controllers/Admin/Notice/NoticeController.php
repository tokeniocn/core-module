<?php

namespace Modules\Core\Http\Controllers\Admin\Notice;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Notice\NoticeRequest;
use Modules\Core\Services\Frontend\NoticeService;

/**
 * Class SettingsController
 */
class NoticeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('core::admin.notice.index');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('core::admin.notice.create');
    }

    /**
     * @param Request $request
     * @param NoticeService $noticeService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, NoticeService $noticeService)
    {
        return view('core::admin.notice.update', [
            'notice' => $noticeService->getByKey($request->key)
        ]);
    }

    public function update(NoticeRequest $request, NoticeService $noticeService)
    {
        $noticeService->update($request->key, $request->all());
        return response()->redirectTo(route('admin.notice.edit', ['key' => $request->key]));
    }

}
