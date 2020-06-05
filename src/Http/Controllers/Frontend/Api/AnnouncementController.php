<?php

namespace Modules\Core\Http\Controllers\Frontend\Api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\AnnouncementService;

class AnnouncementController extends Controller
{
  public function index(Request $request, AnnouncementService $labelService)
  {
    return $labelService->paginate();
  }

  public function info(Request $request, AnnouncementService $labelService)
  {
    return $labelService->getLabelInfoByLabel($request->label);
  }
}
