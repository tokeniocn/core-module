<?php

namespace Modules\Core\Http\Controllers\Admin\Announce;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Announce\AnnounceRequest;
use Modules\Core\Services\Frontend\AnnounceService;

/**
 * Class SettingsController
 */
class AnnounceController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('core::admin.announce.index');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('core::admin.announce.create');
    }

    /**
     * @param Request $request
     * @param AnnounceService $announceService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, AnnounceService $announceService)
    {
        return view('core::admin.announce.update', [
            'announce' => $announceService->getByKey($request->key)
        ]);
    }

    public function update(AnnounceRequest $request, AnnounceService $announceService)
    {
        $announceService->update($request->key, $request->all());
        return response()->redirectTo(route('admin.announce.edit', ['key' => $request->key]));
    }

}
