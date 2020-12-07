<?php


namespace Modules\Core\Http\Controllers\Admin\Api\Auth;


use App\Models\AdminUser;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\User\AdminUserRequest;

class AdminUsersController extends Controller
{

    public function index(Request $request)
    {
        $list = AdminUser::query()->with('rules')->paginate();

        foreach ($list as $item) {
            $item->rule_title = $item->rules->title ?? '超级管理员';
            $item->active_text = $item->active_text;
        }

        return $list;
    }

    public function create(AdminUserRequest $request)
    {

        $data = [
            'rules_id' => $request->input('rules_id'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'active' => $request->input('active'),
        ];
        if(strlen($data['password'])<6){
            throw new \Exception('密码长度至少6位');
        }

        $info = AdminUser::query()->where('username', $data['username'])
            ->first();
        if ($info) {
            throw new \Exception('该管理员名称已经存在');
        }

        AdminUser::query()->create($data);
        return ['msg' => '添加成功'];
    }


    public function update(AdminUserRequest $request)
    {

        $data = [
            'rules_id' => $request->input('rules_id'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'active' => $request->input('active'),
        ];

        $id = $request->input('id');
        $info = AdminUser::query()->where('username', $data['username'])
            ->first();
        if ($info) {
            if ($info->id != $id) {
                throw new \Exception('该管理员名称已经存在');
            }
        }

        if ($data['password']) {
            if(strlen($data['password'])<6){
                throw new \Exception('密码长度至少6位');
            }
            $info->password = $data['password'];
        }
        $info->rules_id = $data['rules_id'];
        $info->username = $data['username'];
        $info->active = $data['active'];

        $info->save();
        return ['msg' => '修改成功'];
    }

}
