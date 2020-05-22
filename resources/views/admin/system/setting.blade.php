@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post" class="layui-form" action="{{route('admin.system.settings.update')}}">
                {{csrf_field()}}
                <x-t-config-form :list="$configList"></x-t-config-form>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        layui.use(['upload', 'form'], function () {
            var $ = layui.jquery
                , upload = layui.upload;

            //普通图片上传
                    @foreach($configList as $config)
                    @if($config['type'] === 'image')
            var uploadInst = upload.render({
                    elem: '#{{$config['key']}}_btn'
                    , url: '{{route('admin.api.media.upload')}}'
                    , before: function (obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function (index, file, result) {
                            $('#{{$config['key']}}_image').attr('src', result); //图片链接（base64）
                        });
                    }
                    , done: function (res) {
                        if (res.message === undefined) {
                            //上传成功
                            $("input[name='{{$config['key']}}']").val(res.path);
                        } else {
                            layer.msg('上传失败');
                        }
                    }
                    , error: function () {
                    }
                });
            @elseif($config['type'] ==='image_list')
            //多图片上传
            upload.render({
                elem: '#{{$config['key']}}_btn'
                , url: '{{route('admin.api.media.upload')}}'
                , multiple: true
                , done: function (res) {
                    if (res.message === undefined) {
                        //上传成功
                        $('#{{$config['key']}}_image').append('<div class="image-preview-box"><img src="' + res.url + '" class="layui-upload-img"><input type="hidden" name="{{$config['key']}}[]" value="' + res.path + '"/><button type="button" class="layui-btn layui-btn-danger layui-btn-xs">删除</button></div>')
                        imageBindRemove();
                    } else {
                        layer.msg('上传失败');
                    }
                }
            });

            @endif
            @endforeach

            function imageBindRemove() {
                $(".image-preview-box > .layui-btn").unbind('click').bind('click', function () {
                    $(this).parent().remove();
                });
            }

            imageBindRemove();
        });
    </script>
@endpush
<style>
    .layui-form-item .layui-input-inline {
        width: 400px !important;
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


