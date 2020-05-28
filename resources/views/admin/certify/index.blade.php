@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <form class="layui-card-body">
            <form class="layui-form" action="">
                <div class="layui-inline">
                    <input type="text" name="id" value="{{isset($id)?$id:''}}" class="layui-input" id="id" placeholder="表ID">
                </div>
                <div class="layui-inline">
                    <input type="text" name="keyword" value="{{isset($keyword)?$keyword:''}}"class="layui-input" id="keyword" placeholder="搜索用户真实姓名">
                </div>
                <div class="layui-inline">
                    <select name="status" id="status" lay-verify="required" class="layui-select">
                        <option value="">--实名认证--</option>
                        @foreach ($status_list as $key=>$text)
                            <option value="{{ $key }}" {{isset($status)?$key === $status?'checked':'':''}}>{{ $text }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="layui-btn" data-type="reload">搜索</button>
            </form>


            <script type="text/html" id="toolColumn">
                <a class="layui-btn layui-btn-xs" lay-event="edit">审核</a>
            </script>
            <table id="lay-table" lay-filter="lay-table">
                <thead>
                <tr>
                    <th lay-data="{field: 'id'}">ID</th>
                    <th lay-data="{field: 'user_name'}">账号名</th>
                    <th lay-data="{field: 'mobile'}">手机号</th>
                    <th lay-data="{field: 'name'}">真实姓名</th>
                    <th lay-data="{field: 'certify_type_text'}">证件类型</th>
                    <th lay-data="{field: 'number',width:200}">证件号码</th>
                    <th lay-data="{field: 'obverse'}">证件照正面</th>
                    <th lay-data="{field: 'reverse'}">证件照发面</th>
                    <th lay-data="{field: 'status_text'}">审核状态</th>
                    <th lay-data="{field: 'status'}">状态</th>
                    <th lay-data="{field: 'created_at'}">提交时间</th>
                    <th lay-data="{field: 'right',toolbar: '#toolColumn'}">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($certify_list as $key=>$certify)
                    <tr>
                        <td>{{$certify->id}}</td>
                        <td>{{$certify->user->username}}</td>
                        <td>{{$certify->user->mobile}}</td>
                        <td>{{$certify->name}}</td>
                        <td>{{$certify->certify_type_text}}</td>
                        <td>{{$certify->number}}</td>
                        <td>
                            <a href="{{$certify->obverse}}" target="_blank">查看
                            </a>
                        </td>
                        <td><a href="{{$certify->reverse}}" target="_blank">查看</a></td>
                        <td>{{$certify->status_text}}</td>
                        <td>{{$certify->status}}</td>
                        <td>{{$certify->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="model" style="padding:10px;background:#FFFFFF;display:none;width:500px;">
            <div class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label" style="padding-left:0px;padding-right: 0px;">审核状态:</label>
                    <div class="layui-input-block">
                        @foreach ($status_list as $key=>$text)
                            <input type="radio" name="update_status" value="{{$key}}" title="{{$text}}">
                        @endforeach
                    </div>
                </div>
                <input type="hidden" value="" id="hidden_id">
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')

    <script>
        layui.use(['form', 'table', 'util'], function () {

            var $ = layui.$
                , util = layui.util
                , form = layui.form
                , table = layui.table;

            table.init('lay-table', {
                page: {
                    layout: ['count', 'prev', 'page', 'next', 'skip'],
                    count:1000,
                    groups:5
                }
            });
            $("#status").val('{{isset($status)?$status:''}}');
            form.render();
            table.on("tool(lay-table)", function (e) {
                var data = e.data;
                if (e.event === 'edit') {
                    $("#hidden_id").val(data.id);
                    $("input[name='update_status']").each(function (index, obj) {
                        if ($(obj).val() == data.status) {
                            $(obj).next().find('i').click();
                        } else {
                            $(obj).attr('checked', false);
                        }
                    });
                    form.render();
                    layer.open({
                        type: 1,
                        title: false,
                        closeBtn: 0,
                        area: ['500px', '120px'],
                        shadeClose: true,
                        skin: 'yourclass',
                        content: $("#model"),
                        btn: ['确定修改', '取消'],
                        yes: function (index, layero) {
                            let id = $("#hidden_id").val();
                            let status = $("input[name='update_status']:checked").val();
                            $.ajax({
                                url: "{{route('admin.api.certify.update',['id'=>'!id!'])}}".replace('!id!', id),
                                type: "post",
                                data: {id: id, status: status},
                                success: function (res) {
                                    window.location.reload();
                                }
                            });
                            layer.close(index)
                        }
                    });
                }
            });
        })
    </script>
@endpush

