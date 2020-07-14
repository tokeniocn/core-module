@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">

            <div class="test-table-reload-btn" style="margin-bottom: 10px;">

                <button class="layui-btn" data-type="create">添加</button>
            </div>

            <table id="LAY-user-back-role" lay-filter="LAY-user-back-role"></table>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script type="text/html" id="table-useradmin-admin">

        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>

    </script>

    <script>

        layui.use(['form', 'table', 'util', 'laydate'], function () {

            var $ = layui.$
                , util = layui.util
                , form = layui.form
                , table = layui.table
                , laydate = layui.laydate;

            laydate.render({
                elem: '#laydate-range-datetime'
                , type: 'date'
                , range: '||'
            });

            table.render({
                elem: '#LAY-user-back-role',
                toolbar: '#tableToolbar',
                url: '{{ route('admin.api.label.index') }}',
                method: 'post',
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg': res.message || '加载失败', //解析提示文本
                        'count': res.total || 0, //解析数据长度
                        'data': res.data || [] //解析数据列表
                    };
                },
                cols: [[
                    {fixed: 'left', title: '操作', toolbar: '#table-useradmin-admin', width: 120}
                    , {field: 'key',title: 'KEY',}
                    ,{field: 'remark',title: '说明'}
                    , {field: 'value', title: '内容值'}
                ]],
                text: {
                    none: '无相关数据'
                },
                page: true
            });



            table.on("tool(LAY-user-back-role)", function (e) {
                if (events[e.event]) {
                    events[e.event].call(this, e.data);
                }

                var data = e.data;
                if(e.event==='edit') {

                    var url = '{{ route('admin.label.update') }}?id='+data.id;
                    layer.open({
                        type: 2
                        , title: '编辑标签信息'
                        , content: url
                        , area: ['90%', '90%']
                    })
                }

            });
            util.event('lay-event', events);


            var events = {};


            //搜搜重载
            var $ = layui.$, active = {
                reload: function () {

                    //执行重载
                    table.reload('LAY-user-back-role', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        , where: {

                        }
                    });
                },

                create:function () {

                    var url = '{{ route('admin.label.create') }}';
                    layer.open({
                        type: 2
                        , title: "添加Label"
                        , content: url
                        , area: ['90%', '90%']
                    })
                }

            };

            $('.test-table-reload-btn .layui-btn').on('click', function () {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

        })
    </script>
@endpush
