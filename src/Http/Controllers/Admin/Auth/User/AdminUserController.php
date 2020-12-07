<?php


namespace Modules\Core\Http\Controllers\Admin\Auth\User;


use App\Models\AdminUser;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Admin\AdminRole;

class AdminUserController extends Controller
{

    public function index()
    {

        return view('core::admin.user.admin.index');
    }


    public function create(){

        $rules = AdminRole::query()->get();
        return view('core::admin.user.admin.create',[
            'rules'=>$rules
        ]);
    }

    public function update(Request $request){

        $rules = AdminRole::query()->get();
        $id = $request->input('id');
        $info = AdminUser::query()->where('id',$id)->first();
        return view('core::admin.user.admin.update',[
            'rules'=>$rules,
            'info'=>$info
        ]);

    }

}
