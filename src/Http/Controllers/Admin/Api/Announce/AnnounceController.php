<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Announce;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Announce\AnnounceRequest;
use Modules\Core\Services\Frontend\AnnounceService;

class AnnounceController extends Controller
{
    /**
     * @param Request $request
     * @param AnnounceService $announceService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, AnnounceService $announceService)
    {
        return $announceService->all([], [
            'paginate' => true
        ]);
    }

    public function create(AnnounceRequest $request, AnnounceService $announceService)
    {
        return $announceService->store($request->all());
    }

    public function update(AnnounceRequest $request, AnnounceService $announceService)
    {
        return $announceService->update($request->id, $request->all());
    }

    public function delete(AnnounceRequest $request, AnnounceService $announceService)
    {
        return $announceService->deleteById((int)$request->id);
    }
}
