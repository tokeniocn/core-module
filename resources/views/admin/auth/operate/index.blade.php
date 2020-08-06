@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="LAY-user-back-role" lay-filter="LAY-user-back-role"></table>
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
                elem: '#LAY-user-back-role',
                toolbar: '#tableToolbar',
                url: '{{ route('admin.api.operate.index') }}',
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg': res.message || '加载失败', //解析提示文本
                        'count': res.total || 0, //解析数据长度
                        'data': res.data || [] //解析数据列表
                    };
                },
                cols: [[
                    {field: 'id', width: 80, title: 'ID',},
                    {field: 'user_id', title: '操作人员ID',width:150},
                    {field: 'scene_text', title: '类型',width:150},
                    {field: 'category', title: '分类',width:150},
                    {field: 'operate', title: '操作',width:150},
                    {field: 'created_at',title: '时间',width:200,templet: function (res) {
                            return moment(res.created_at).format("YYYY-MM-DD HH:mm:ss")}},

                    {field: 'log', title: '详情'},
                    /*{ title: '操作',width: 150,align: 'center',fixed: 'right',toolbar: '#table-useradmin-admin'}*/
                ]],
                text: {
                    none: '还没有创建角色'
                },
                page: true
            });
            table.on("tool(LAY-user-back-role)", function (e) {
                if (events[e.event]) {
                    events[e.event].call(this, e.data);
                }
            });

        })
    </script>
@endpush

