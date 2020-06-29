@extends('core::admin.layouts.app')
<link rel="stylesheet" href="/vendor/editormd/css/editormd.css"/>
@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post" class="layui-form">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label class="layui-form-label">Key</label>
                    <div class="layui-input-inline">
                        <input type="text" name="key" value=""
                               placeholder="请输入Key" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">参数最少要有两级，最多只能三级。比如system.name(两级)，system.share.remark(三级)，确定后无法修改</div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">值</label>
                    <div class="layui-input-inline">
                        <div id="markdown_box">
                            <textarea style="display: none;" type="text" id="value" name="value" autocomplete="off" class="layui-textarea">
                        </textarea>
                        </div>

                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">label说明</label>
                    <div class="layui-input-inline">
                        <input type="text" name="remark" value=""
                               placeholder="请输入备注内容" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        {{--<button type="submit" class="layui-btn" lay-submit="" lay-filter="lay-announce">立即提交</button>--}}
                        <button class="layui-btn" lay-submit lay-filter="add">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('after-scripts')
    <script>
        layui.use(['form'], function () {
            let $ = layui.$
                , form = layui.form;


            $.getScript("/vendor/editormd/js/editormd.js", function() {
                let editor = editormd("markdown_box", {
                    height: 250,
                    toolbarIcons: function () {
                        return ["undo", "redo", "|", "bold", "del", "italic", "quote", "uppercase", "|", "h1", "h2", "h3", "h4", "h5", "h6", "|", "preview", "watch"]
                    },
                    watch: false,
                    path: "/vendor/editormd/lib/"
                });
            });

            form.on('submit(add)', function(data){

                var url = '{{ route('admin.label.store') }}';
                $.post(url,data.field,function(res){

                    console.log(res);
                    layer.msg('添加成功',{icon: 1,time: 2000,shade: [0.8, '#393D49']},function(){
                        window.parent.location.reload();
                    });

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
