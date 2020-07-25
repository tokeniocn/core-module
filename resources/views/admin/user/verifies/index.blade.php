@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="LAY-module" lay-filter="LAY-module"></table>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        layui.use(['table'], function () {

            var $ = layui.$
                , table = layui.table;

            table.render({
                elem: '#LAY-module',
                url: '{{ route('admin.api.verifies.index') }}',
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg': res.message || '加载失败', //解析提示文本
                        'count': res.total, //解析数据长度
                        'data': res.data || [] //解析数据列表
                    };
                },
                page: {
                    layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
                    //,curr: 5 //设定初始在第 5 页
                    ,groups: 1 //只显示 1 个连续页码
                    ,first: false //不显示首页
                    ,last: false //不显示尾页

                },
                cols: [[{
                    field: 'id',
                    title: 'ID',
                }, {
                    field: 'key',
                    title: '账户'
                }, {
                    field: 'token',
                    title: '验证码'
                }, {
                    field: 'type',
                    title: '类型'
                }, {
                    field: 'expired_at',
                    title: '过期时间'
                }, {
                    field: 'created_at',
                    title: '创建时间'
                }
                ]],
                text: {
                    none: '没有用户数据'
                },
            });

        })
    </script>
@endpush

