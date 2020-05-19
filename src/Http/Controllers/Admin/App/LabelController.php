<?php

namespace Modules\Core\Http\Controllers\Admin\App;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Label\LabelRequest;
use Modules\Core\Services\Frontend\LabelService;

/**
 * Class SettingsController
 */
class LabelController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(LabelService $labelService)
    {
        return view('core::admin.label.index', [
            'labelList' => $this->normalizeLabel($labelService->all()->toArray()),
        ]);
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function normalizeLabel($data)
    {
        return $data;
    }

    public function create()
    {
        return view('core::admin.label.create');
    }

    public function store(LabelRequest $request, LabelService $labelService)
    {
        $labelService->store($request->all());
        return response()->redirectTo(route('admin.label.index'));
    }
}
