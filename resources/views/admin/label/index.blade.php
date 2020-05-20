@extends('core::admin.layouts.app')
<link rel="stylesheet" href="/vendor/editormd/css/editormd.css"/>
@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post" class="layui-form" action="{{route('admin.api.label.update')}}">
                {{csrf_field()}}
                @foreach($labelList as $label)
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{$label['key']}}</label>
                        <div class="layui-input-inline">
                            <div id="markdown_{{$label['id']}}">
                                <textarea type="text" name="{{$label['key']}}" value="{{$label['value']}}"
                                          placeholder="请输入{{$label['remark']}}" autocomplete="off"
                                          class="layui-textarea">{{$label['value']}}</textarea>
                            </div>
                        </div>
                        <div class="layui-form-mid layui-word-aux">{{$label['remark']}}</div>
                    </div>
                @endforeach
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="lay-label">立即提交</button>
                        <a href="{{route('admin.label.create')}}" class="layui-btn layui-btn-primary">添加Label</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('after-scripts')
    <script src="/vendor/zepto/zepto-1.2.0.js"></script>
    <script src="/vendor/editormd/js/editormd.js"></script>
    <script>
        layui.use(['form'], function () {
            var $ = layui.$
            @foreach($labelList as $key=>$label)
                let editor_{{$key}} = editormd("markdown_{{$label['id']}}", {
                    height: 250,
                    toolbarIcons: function () {
                        return ["undo", "redo", "|", "bold", "del", "italic", "quote", "uppercase", "|", "h1", "h2", "h3", "h4", "h5", "h6", "|", "preview", "watch"]
                    },
                    watch: false,
                    path: "/vendor/editormd/lib/"
                });
            @endforeach
        });
    </script>
@endpush
<style>
    .layui-form-item .layui-input-inline {
        width: 1000px !important;
    }

    .layui-upload-img {
        width: 100px;
        height: 100px;
        margin: 10px;
    }

    .layui-upload-list {
        overflow: hidden;
    }

    .layui-form-label {
        box-sizing: initial;
        width: 200px !important;
    }

    .layui-form-checkbox {
        display: inline-flex !important;

    }

    .layui-form-checkbox span {
        font-size: 15px !important;
        color: #000 !important;
    }

    .image-preview-box {
        float: left;
        overflow: hidden;
        text-align: center;
        display: inline-flex;
        flex-direction: column;
    }

    .image-preview-box .layui-btn {
        margin: 0px 10px;
    }
</style>


