@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="test-table-reload-btn" style="margin-bottom: 10px;">
                <button class="layui-btn" data-type="create">添加新管理员</button>
            </div>


            <script type="text/html" id="toolColumn">

                <a class="layui-btn layui-btn-xs" lay-event="edit_user">编辑</a>

            </script>
            <table id="lay-table" lay-filter="lay-table"></table>
        </div>

    </div>
@endsection

@push('after-scripts')

    <script>
        layui.use(['form', 'table', 'util', 'laydate'], function () {

            var $ = layui.$
                , util = layui.util
                , form = layui.form
                , table = layui.table
                , laydate = layui.laydate;

            laydate.render({
                elem: '#created_at'
                , type: 'date'
                , range: '||'
            });

            table.render({
                elem: '#lay-table',
                url: '{{ route('admin.api.admin_users.index') }}',
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
                id:"dataTable",
                cols: [[
                    {field: 'right',title: '操作',toolbar: '#toolColumn',width:120},
                    {field: 'id',title: 'ID',width:80},
                    {field: 'username',title: '管理员',width:200},
                    {field: 'rule_title',title: '角色组',width:200},
                    {field: 'active_text',title: '状态',width:120},
                    {field: 'created_at',title: '创建时间',width:170,templet: function (res) {
                            return moment(res.created_at).format("YYYY-MM-DD HH:mm:ss")}}
                ]],
                text: {
                    none: '没有可用数据'
                },
            });
            table.on("tool(lay-table)", function (e) {
                // if (events[e.event]) {
                //     events[e.event].call(this, e.data);
                // }
                var data = e.data;
                if(e.event ==='edit_user'){

                    var url = '{{ route('admin.admin_user.update') }}?id='+data.id;
                    layer.open({
                        type: 2
                        , title: '编辑：' + data['username']
                        , content: url
                        , area: ['90%', '90%']
                    })
                }
            });



            let active = {
                reload: function(){

                    //执行重载
                    table.reload('dataTable', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            state: $("#state").val(),
                            symbol: $("#coin").val(),
                        }
                    }, 'data');
                },
                create:function(){

                    var url = '{{ route('admin.admin_user.create') }}';
                    layer.open({
                        type: 2
                        , title: '添加'
                        , content: url
                        , area: ['90%', '90%']
                    })
                }
            };


            $('.layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        })

    </script>
@endpush

