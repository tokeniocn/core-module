@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post"  class="layui-form" lay-filter="test1">
                {{csrf_field()}}


                <div class="layui-form-item">
                    <label class="layui-form-label">管理员登录名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="username" value="{{$info->username}}" required autocomplete="off" class="layui-input">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">登录密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password" required placeholder="至少6位" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">确定密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="re_password" required placeholder="至少6位" autocomplete="off" class="layui-input">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="active" value="1" title="启用" @if($info->active==1)  checked @endif>
                        <input type="radio" name="active" value="0" title="禁用" @if($info->active==0)  checked @endif>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">权限角色</label>
                    <div class="layui-input-inline">
                        <select name="rules_id" lay-verify="required">
                            @foreach ($rules as $key=> $vo)
                                <option value="{{ $vo->id }}" @if($info->rules_id== $vo->id ) selected @endif>{{ $vo->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <input type="hidden" name="id" value="{{$info->id}}">
                        <button class="layui-btn" lay-submit lay-filter="add">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('after-scripts')
    <script>
        layui.use(['form', 'table', 'layedit','laydate'], function () {
            var $ = layui.$
                , form = layui.form
                , laydate = layui.laydate;
            form.render(null, 'test1');
            laydate.render({
                elem: '#start_time'
                , type: 'datetime'
            });

            form.on('submit(add)', function(data){

                var url = '{{ route('admin.api.admin_users.update') }}';
                $.post(url,data.field,function(res){

                    console.log(res);

                    //if(res.code==200){

                    layer.msg(res.msg,{icon: 1,time: 2000,shade: [0.8, '#393D49']},function(){

                        window.parent.location.reload();
                    });

                    /*}else{
                        layer.msg(res.msg, {time: 2000});
                    }*/
                },'json');
                return false;
            });

        })
    </script>
@endpush

<style>
    .layui-form-item .layui-input-inline {
        width: 800px !important;
    }

    .layui-form-label {
        box-sizing: initial;
        width: 200px !important;
    }
</style>
