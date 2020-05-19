@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="lay-notice" lay-filter="lay-notice"></table>
        </div>
    </div>
    <script type="text/html" id="action-box">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
{{--        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>--}}
    </script>

@endsection

@push('after-scripts')
    <script>
        layui.use(['form', 'table', 'util'], function () {
            var $ = layui.$
                , util = layui.util
                , form = layui.form
                , table = layui.table;
            table.render({
                elem: '#lay-notice',
                url: '{{ route('admin.api.notice.index') }}',
                toolbar: '#bar-header-box',
                defaultToolbar: ['filter', {
                    title: '提示'
                    , layEvent: 'add'
                    , icon: 'layui-icon-addition'
                }],
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg': res.message || '解析成功', //解析提示文本
                        'count': res.total, //解析数据长度
                        'data': res.data || [] //解析数据列表
                    }
                },
                cols: [[{
                    field: 'key',
                    title: 'ID',
                }, {
                    field: 'title',
                    title: '标题'
                }, {
                    field: 'created_at',
                    title: '创建时间'
                }, {
                    field: 'updated_at',
                    title: '更新时间'
                }, {
                    title: '操作',
                    width: 150,
                    align: 'center',
                    fixed: 'right',
                    toolbar: '#action-box',
                }]],
                text: {
                    none: '没有可用数据'
                },
            });

            table.on('toolbar(lay-notice)', function (obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'add':
                        location.href = '{{ route('admin.notice.create') }}';
                        break;
                }
            });
            table.on('tool(lay-notice)', function (obj) {
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        obj.del();
                        layer.close(index);
                    });
                } else if(obj.event === 'edit'){
                    location.href = '{{ route('admin.notice.update',['key' => '!key!']) }}'.replace('!key!', data.key)
                }
            });

        })
    </script>
@endpush

