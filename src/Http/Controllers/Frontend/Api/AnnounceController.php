<?php


namespace Modules\Core\Http\Controllers\Frontend\Api;


use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\AnnounceService;

class AnnounceController extends Controller
{
    public function index(Request $request, AnnounceService $announceService)
    {
        return $announceService->all([], ['paginate' => true]);
    }

    public function info(Request $request, AnnounceService $announceService)
    {
        return $announceService->getById($request->id);
    }
}