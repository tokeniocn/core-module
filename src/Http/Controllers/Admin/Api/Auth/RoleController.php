<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Auth;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Modules\Core\Events\Admin\Auth\Role\RoleDeleted;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\Role\ManageRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\StoreRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\UpdateRoleRequest;
use Modules\Core\Models\Admin\AdminRole;
use Modules\Core\Models\Frontend\Role;
use Modules\Core\Repositories\Admin\Auth\RoleRepository;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

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
    public function index(ManageRoleRequest $request, RoleRepository $roleRepository)
    {
        return $roleRepository
            ->where('guard_name', $request->get('guard', Auth::getDefaultDriver()))
            ->orderBy('sort')
            ->paginate();
    }

    public function create(StoreRoleRequest $request)
    {

        $data = [
            'title' => $request->input('title'),
            'name' => $request->input('name'),
            'sort' => $request->input('sort'),
            'rules' => $request->input('rule'),
        ];

        $data['rules'] = array_merge($data['rules']);
        AdminRole::query()->create($data);
        return ['msg' => '添加成功'];
    }


    public function update(StoreRoleRequest $request)
    {

        $data = [
            'title' => $request->input('title'),
            'name' => $request->input('name'),
            'sort' => $request->input('sort'),
            'rules' => $request->input('rule'),
        ];
        $data['rules'] = array_merge($data['rules']);
        $id = $request->input('id');

        AdminRole::query()->where('id', $id)->update($data);
        return ['msg' => '添加成功'];
    }


    public function del(Request $request)
    {

        $id = $request->input('id');
        if ($id == 1) {
            throw new \Exception('超级管理员角色组不能删除');
        }

        $info = AdminRole::query()->where('id', $id)->first();
        if ($info) {

            $admin = AdminUser::query()->where('rules_id', $id)->count();
            if ($admin) {
                throw new \Exception('该角色下面还有管理员,不能直接删除');
            }

            $info->delete();
            return [
                'msg' => '删除成功'
            ];
        } else {

            throw new \Exception('该管理员不存在');
        }
    }

}
