<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Operate;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Services\OperateLogService;

class OperateLogController extends Controller
{

    public function index(Request $request, OperateLogService $operateLogService)
    {

        $result = $operateLogService->paginate([], [
            'orderBy' => ['id', 'desc'],
        ]);

        foreach ($result as $item) {
            $item->scene_text = $item->scene_text;
        }

        return $result;
    }

}
