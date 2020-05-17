@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post" class="layui-form" action="{{route('admin.app.settings.update')}}">
                {{csrf_field()}}
                @foreach($configList as $config)
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{$config['title']}}</label>
                        <div class="layui-input-inline">
                            @if($config['type'] === 'image')
                                <div class="layui-upload">
                                    <button type="button" class="layui-btn" id="{{$config['key']}}_btn">上传{{$config['title']}}</button>
                                    <div class="layui-upload-list">
                                        @if($config['value'] ==='')
                                            <img src="" class="layui-upload-img" id="{{$config['key']}}_image">
                                        @else
                                            <img src="{{$config['value']}}" class="layui-upload-img" id="{{$config['key']}}_image">
                                        @endif
                                        <p id="{{$config['key']}}_text"></p>
                                    </div>
                                </div>
                                <input type="hidden" name="{{$config['key']}}" value="{{$config['value']}}"
                                       placeholder="请输入{{$config['title']}}" autocomplete="off" class="layui-input">
                            @elseif($config['type'] === 'image_list')
                                <div class="layui-upload">
                                    <button type="button" class="layui-btn" id="{{$config['key']}}_btn">上传{{$config['title']}}</button>
                                    <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                        预览图：
                                        <div class="layui-upload-list" id="{{$config['key']}}_image">
                                            @foreach($config['value'] as $image)
                                                <div class="image-preview-box">
                                                    <img src="{{$image}}" class="layui-upload-img">
                                                    <input type="hidden" name="{{$config['key']}}[]" value="{{$image}}"/>
                                                    <button type="button" class="layui-btn layui-btn-danger layui-btn-xs">删除</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </blockquote>
                                </div>
                            @elseif($config['type'] === 'radio')
                                @foreach($config['data'] as $key)
                                    <input type="radio" name="{{$config['key']}}" name="{{$key}}" title="{{$key}}"
                                           @if($config['value'] == $key)
                                           checked
                                        @endif
                                    />
                                @endforeach
                            @elseif($config['type'] === 'checkbox')
                                @foreach($config['data'] as $key)
                                    <input type="checkbox" name="{{$config['key']}}[{{$key}}]" lay-skin="primary" title="{{$key}}"
                                        @if(in_array($key, $config['value']))
                                           checked
                                        @endif
                                    />
                                @endforeach
                            @else
                                <input type="text" name="{{$config['key']}}" value="{{$config['value']}}"
                                       placeholder="请输入{{$config['title']}}" autocomplete="off" class="layui-input">
                            @endif
                        </div>
                        <div class="layui-form-mid layui-word-aux">{{$config['description']}}</div>
                    </div>
                @endforeach
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
        layui.use('upload', function () {
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
                        ,url: '{{route('admin.api.media.upload')}}'
                        ,multiple: true
                        ,done: function(res){
                            if (res.message === undefined) {
                                //上传成功
                                $('#{{$config['key']}}_image').append('<div class="image-preview-box"><img src="'+ res.url +'" class="layui-upload-img"><input type="hidden" name="{{$config['key']}}[]" value="'+res.path+'"/><button type="button" class="layui-btn layui-btn-danger layui-btn-xs">删除</button></div>')
                                imageBindRemove();
                            } else {
                                layer.msg('上传失败');
                            }
                        }
                    });
                @endif
            @endforeach

            function imageBindRemove()
            {
                $(".image-preview-box > .layui-btn").unbind('click').bind('click',function(){
                    $(this).parent().remove();
                });
            }
            imageBindRemove();
        });
    </script>
@endpush
<style>
    .layui-form-label {
        box-sizing: initial;
    }

    .layui-form-item .layui-input-inline {
        width: 400px !important;
    }

    .layui-upload-img {
        width: 100px;
        height: 100px;
        margin:10px;
    }
    .layui-upload-list{
        overflow: hidden;
    }
    .image-preview-box{
        float: left;
        overflow: hidden;
        text-align: center;
        display: inline-flex;
        flex-direction: column;
    }
    .image-preview-box .layui-btn{
        margin: 0px 10px;
    }
</style>


