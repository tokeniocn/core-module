@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post"  class="layui-form" lay-filter="test1">
                {{csrf_field()}}

                <div class="layui-form-item">
                    <label class="layui-form-label">角色名称</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" lay-verify="required" type="text" name="title"  placeholder="请输入角色名称" />
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">角色关键字</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" lay-verify="required" type="text" name="name"  placeholder="请输入角色关键字,纯英文" />
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" type="number" value="0" name="sort"   />
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">角色权限：</label>
                </div>
                <div style="border: 2px solid #d2d2d2;width: 80%;margin-left: 10%;">
                    @foreach ($menu as $vo)
                        <div class="layui-form-item" style="border-bottom: 1px solid #ccc;">

                            <p>
                                <input type="checkbox" style="" name="rule[]" value="{{$vo->id}}" title="{{$vo->title}}" >
                            </p>
                            <div class="layui-input-inline">

                                <div class="layui-col-md12" style="margin-left:40px;">
                                    @if ($vo->sons)
                                        @foreach ($vo->sons as $v2)
                                            <li style="float: left;">
                                                <input  type="checkbox" name="rule[]" value="{{$v2->id}}" title="{{$v2->title}}" >
                                            </li>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
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

                var url = '{{ route('admin.api.auth.role.create') }}';
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

