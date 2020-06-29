@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            {{--            <form class="layui-form" action="">--}}
            <div class="layui-inline">
                <input type="text" name="id" value="{{isset($id)?$id:''}}" class="layui-input" id="id"
                       placeholder="表ID">
            </div>
            <div class="layui-inline">
                <input type="text" name="user_info" value="{{isset($user_info)?$user_info:''}}" class="layui-input" id="user_info"
                       placeholder="会员UID|会员名">
            </div>
            <div class="layui-inline">
                <input type="text" name="keyword" value="{{isset($keyword)?$keyword:''}}" class="layui-input"
                       id="keyword" placeholder="搜索用户真实姓名">
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
            {{--            </form>--}}


            <script type="text/html" id="toolColumn">
                <a class="layui-btn layui-btn-xs" lay-event="edit">审核</a>
            </script>
            <table id="lay-table" lay-filter="lay-table">

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

            table.render({
                elem: '#lay-table',
                url: '{{ route('admin.api.certify.index') }}',
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg': res.message || '加载失败', //解析提示文本
                        'count': res.total, //解析数据长度
                        'data': res.data || [] //解析数据列表
                    };
                },
                page: {
                    layout: ['count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
                },
                id: "dataTable",
                cols: [[
                    {
                        field: 'right',
                        title: '操作',
                        toolbar: '#toolColumn'
                    },
                    {field: 'id', title: 'ID'},
                    {field: 'user_id', title: 'UID'},
                    {field: 'user_name', title: '账户名',templet: function (res) {
                            return res.user.username
                        }
                    },
                    {field: 'mobile', title: '手机号',templet: function (res) {
                            return res.user.mobile
                        }
                    },
                    {field: 'name', title: '姓名'},
                    {field: 'certify_type_text', title: '证件类型'},
                    {field: 'number', title: '证件号码', width: 200},
                    , {
                        field: 'obverse', title: '身份证正面', width: 110, event: 'show_obverse', templet: function (res) {
                            return "<img src='" + res.obverse + "' style='height: 80px;width: 80px'>"
                        }
                    }
                    , {
                        field: 'reverse', title: '身份证反面', width: 110, event: 'show_reverse', templet: function (res) {
                            return "<img src='" + res.reverse + "' style='height: 80px;width: 80px'>"
                        }
                    },
                    {
                        field: 'status', title: '状态', templet: function (res) {
                            if (res.status === 0) {
                                return '待审核'
                            } else if (res.status === 1) {
                                return '已通过';
                            } else if (res.status === -1) {
                                return '已驳回';
                            }
                        }
                    },
                    {field: 'created_at', title: '提交时间', templet: function (res) {
                            return moment(res.created_at).format("YYYY-MM-DD HH:mm:ss")
                        }
                    }

                ]],
                text: {
                    none: '没有可用数据'
                },
            });
            $("#status").val('{{isset($status)?$status:''}}');
            let active = {
                reload: function () {
                    let keyword = $('#keyword');
                    let id = $('#id');
                    let status = $('#status');
                    let user_info = $('#user_info');
                    //执行重载
                    table.reload('dataTable', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        , where: {
                            id: id.val(),
                            keyword: keyword.val(),
                            status: status.val(),
                            user_info:user_info.val()
                        }
                    }, 'data');
                }
            };

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
                                    active.reload();
                                }
                            });
                            layer.close(index)
                        }
                    });
                } else if (e.event === 'show_obverse') { //弹出正面图片

                    var imgHtml = "<img src='" + data.obverse + "' width='600px' height='600px'/>";
                    layer.open({
                        type: 1,
                        shade: 0.8,
                        offset: 'auto',
                        area: [600 + 'px', 550 + 'px'], // area: [width + 'px',height+'px'] //原图显示
                        shadeClose: true,
                        scrollbar: false,
                        title: "身份证正面", //不显示标题
                        content: imgHtml, //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                    });
                } else if (e.event === 'show_reverse') { //弹出正面图片

                    var imgHtml = "<img src='" + data.reverse + "' width='600px' height='600px'/>";
                    layer.open({
                        type: 1,
                        shade: 0.8,
                        offset: 'auto',
                        area: [600 + 'px', 550 + 'px'], // area: [width + 'px',height+'px'] //原图显示
                        shadeClose: true,
                        scrollbar: false,
                        title: "身份证反面", //不显示标题
                        content: imgHtml, //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                    });
                }
            });

            $('.layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        })
    </script>
@endpush

