<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Label;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\LabelService;

class LabelController extends Controller
{
    public function index(Request $request, LabelService $labelService)
    {
        $labelService->update($request->all());
        return response()->redirectTo(route('admin.label.index'));
    }
}