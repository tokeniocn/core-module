<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\Role;

use Modules\Core\Events\Admin\Auth\Role\RoleDeleted;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\Role\ManageRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\StoreRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\UpdateRoleRequest;
use Modules\Core\Models\Admin\AdminMenu;
use Modules\Core\Models\Admin\AdminRole;
use Modules\Core\Repositories\Admin\Auth\RoleRepository;

/**
 * Class RoleController.
 */
class RoleController extends Controller
{
    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function index(ManageRoleRequest $request)
    {
        return view('core::admin.auth.role.index');
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function create(ManageRoleRequest $request)
    {

        $menu = AdminMenu::query()->where('parent_id', 0)
            ->where('status', 1)
            ->select("id", 'parent_id', 'title', 'url', 'status', 'sort')
            ->orderBy('sort', 'desc')
            ->get();
        foreach ($menu as $item) {
            $sonsMenu = AdminMenu::query()->where('parent_id', $item->id)
                ->where('status', 1)
                ->select("id", 'parent_id', 'title', 'url', 'status', 'sort')
                ->orderBy('sort', 'desc')
                ->get();
            $item->sons = $sonsMenu;
        }

        return view('core::admin.auth.role.create', [
            'menu' => $menu
        ]);
    }


    public function edit(ManageRoleRequest $request)
    {

        $id = $request->input('id');
        $info = AdminRole::query()->where('id', $id)->first();
        $rules = $info->rules ?? [];

        $menu = AdminMenu::query()->where('parent_id', 0)
            ->where('status', 1)
            ->select("id", 'parent_id', 'title', 'url', 'status', 'sort')
            ->orderBy('sort', 'desc')
            ->get();
        foreach ($menu as $item) {
            $sonsMenu = AdminMenu::query()->where('parent_id', $item->id)
                ->where('status', 1)
                ->select("id", 'parent_id', 'title', 'url', 'status', 'sort')
                ->orderBy('sort', 'desc')
                ->get();
            $item->sons = $sonsMenu;
        }

        return view('core::admin.auth.role.edit', [
            'info' => $info,
            'rules' => $rules,
            'menu' => $menu
        ]);
    }
}
