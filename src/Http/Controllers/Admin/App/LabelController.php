<?php

namespace Modules\Core\Http\Controllers\Admin\App;

use Illuminate\Http\Request;
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
    /*public function index(LabelService $labelService)
    {
        return view('core::admin.label.index', [
            'labelList' => $this->normalizeLabel($labelService->all()->toArray()),
        ]);
    }*/
    public function index()
    {
        return view('core::admin.label.index');
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
        return $labelService->store($request->all());
        //return response()->redirectTo(route('admin.label.index'));
    }

    public function update(Request $request, LabelService $labelService)
    {

        $id = $request->input('id');
        $info = $labelService->getById($id);
        return view('core::admin.label.update', [
            'info' => $info
        ]);
    }

}
