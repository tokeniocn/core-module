@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post" class="layui-form">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label class="layui-form-label">Key</label>
                    <div class="layui-input-inline">
                        <input type="text" name="key" value="{{$label->key}}"
                               placeholder="请输入Key" autocomplete="off" class="layui-input">
                        <div class="layui-form-mid layui-word-aux">参数最少要有两级，最多只能三级。比如system.name(两级)，system.share.remark(三级)，确定后无法修改</div>
                    </div>

                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">值</label>
                    <div class="layui-input-inline">
                        <div class="layui-tab" lay-filter="locale">
                            <ul class="layui-tab-title">
                                @foreach (config('app.supported_locales') as $key => $locale)
                                    <li @if($loop->first)class="layui-this"@endif>{{$locale['name']}}</li>
                                @endforeach
                            </ul>
                            <div class="layui-tab-content" style="padding-left: 0; padding-right: 0">
                                @foreach (config('app.supported_locales') as $key => $locale)
                                    <div
                                        @if($loop->first)
                                        class="layui-tab-item layui-show"
                                        @else
                                        class="layui-tab-item"
                                        @endif>
                                        <div class="editormd" id="{{$key}}-markdown-box">
                                            <textarea type="text" name="value[{{$key}}]" autocomplete="off" class="layui-textarea">{{$label->getTranslation('value', $key)}}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">label说明</label>
                    <div class="layui-input-inline">
                        <input type="text" name="remark" value="{{$label->remark}}"
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
        layui.use(['form', 'element', 'editormd'], function () {
            let $ = layui.$
                , form = layui.form
                , element = layui.element
                , editormd = layui.editormd;

            function initEditor() {
                $('.layui-show .editormd').each(function() {
                    var $this = $(this);
                    if (!$this.data('init')) {
                        editormd($this.attr('id'), {
                            height: 250,
                            toolbarIcons: function () {
                                return ["undo", "redo", "|", "bold", "del", "italic", "quote", "uppercase", "|", "h1", "h2", "h3", "h4", "h5", "h6", "|", "preview", "watch"]
                            },
                            watch: false,
                            path: "/vendor/editormd/lib/"
                        });
                    }

                    $this.data('init', 1)
                });
            }

            initEditor();
            element.on('tab(locale)', initEditor);

            form.on('submit(add)', function(data){

                var url = '{{ $label->exists ? route('admin.label.store', ['id' => $label->id]) : route('admin.label.store') }}';
                $.post(url,data.field,function(res){

                    console.log(res);
                    layer.msg('提交成功',{icon: 1,time: 2000,shade: [0.8, '#393D49']},function(){
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
