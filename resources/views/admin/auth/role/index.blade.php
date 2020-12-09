@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="LAY-user-back-role" lay-filter="LAY-user-back-role"></table>
            <script type="text/html" id="table-useradmin-admin">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i
                        class="layui-icon layui-icon-edit"></i>编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i
                        class="layui-icon layui-icon-delete"></i>删除</a>
            </script>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script type="text/html" id="tableToolbar">
        <div class="layui-btn-container">
            <button class="layui-btn layuiadmin-btn-role" data-type="create">添加角色</button>
        </div>
    </script>
    <script>
        layui.use(['form', 'table', 'util'], function () {

            var $ = layui.$
                , util = layui.util
                , form = layui.form
                , table = layui.table;


            table.render({
                elem: '#LAY-user-back-role',
                toolbar: '#tableToolbar',
                url: '{{ route('admin.api.auth.roles') }}',
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg':res.message || '加载失败', //解析提示文本
                        'count': res.total || 0, //解析数据长度
                        'data': res.data || [] //解析数据列表
                    };
                },
                cols: [[{
                    field: 'id',
                    width: 80,
                    title: 'ID',
                }, {
                    field: 'name',
                    title: '关键字'
                }, {
                    field: 'title',
                    title: '角色名'
                }, {
                    title: '操作',
                    width: 150,
                    align: 'center',
                    fixed: 'right',
                    toolbar: '#table-useradmin-admin'
                }]],
                text: {
                    none: '还没有创建角色'
                },
                page: true
            });

            let active = {

                create:function(){

                    var url = '{{ route('admin.auth.role.create') }}';
                    layer.open({
                        type: 2
                        , title: '添加角色'
                        , content: url
                        , area: ['90%', '90%']
                    })
                },
            };

            table.on("tool(LAY-user-back-role)", function(e) {
                /*if (events[e.event]) {
                    events[e.event].call(this, e.data);
                }*/
                var data = e.data;

                if(e.event ==='edit'){

                    var url = '{{ route('admin.auth.role.edit') }}?id='+data.id;
                    layer.open({
                        type: 2
                        , title: '修改角色'
                        , content: url
                        , area: ['90%', '90%']
                    })
                }

                if(e.event ==='del'){
                    layer.confirm('确定删除吗？', function () {
                        var url = '{{ route('admin.api.auth.role.del') }}?id='+data.id;
                        $.ajax({
                            url: url,
                            type: 'post',
                            success: function() {
                                table.reload('LAY-user-back-role')
                                layer.msg('删除成功', {
                                    offset: '15px',
                                });
                            }
                        });
                    });
                }
            });
            $('.layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
            //util.event('lay-event', events);
        })
    </script>
@endpush

