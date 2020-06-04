@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post" class="layui-form" action="{{route('admin.announce.store')}}">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" value=""
                               placeholder="请输入标题" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">内容</label>
                    <div class="layui-input-inline">
                        <textarea type="text" id="content" name="content"
                                  placeholder="请输入公告内容" autocomplete="off" class="layui-textarea">
                        </textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="lay-announce">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('after-scripts')
    <script>
        layui.use(['form', 'table', 'layedit'], function () {
            var $ = layui.$
                , layedit = layui.layedit
                , form = layui.form
                , table = layui.table;
            layedit.build('content',{
                height: 320,
                tool: [
                    'strong' //加粗
                    ,'italic' //斜体
                    ,'underline' //下划线
                    ,'del' //删除线
                    ,'|' //分割线
                    ,'left' //左对齐
                    ,'center' //居中对齐
                    ,'right' //右对齐
                    ,'link' //超链接
                    ,'unlink' //清除链接
                    ,'face' //表情
                ]
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