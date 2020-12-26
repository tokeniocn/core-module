<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Label;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Label\LabelRequest;
use Modules\Core\Services\Frontend\LabelService;

class LabelController extends Controller
{
    /**
     * @param Request $request
     * @param LabelService $labelService
     * @return \Illuminate\Http\RedirectResponse
     */
    /*public function index(Request $request, LabelService $labelService)
    {
        $labelService->update($request->all());
        return response()->redirectTo(route('admin.label.index'));
    }*/

    public function index(Request $request, LabelService $labelService)
    {
        $list = $labelService->paginate(null, ['exception' => false]);
        return $list;
    }

    /**
     * 修改label
     * @param LabelRequest $request
     * @param LabelService $labelService
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(LabelRequest $request, LabelService $labelService)
    {

        $id = $request->input('id');
        $label = $labelService->getById($id);
        $label->key = $request->input('key');
        $label->value = $request->input('value');
        $label->remark = $request->input('remark');
        $label->save();
        return $label;
    }

    public function delete(Request $request, LabelService $labelService)
    {
        return $labelService->deleteById((int) $request->id);
    }
}
