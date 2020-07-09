@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post" class="layui-form"
                  action="{{$announce->exists ? route('admin.announce.update',['id'=> $announce['id']]) : route('admin.announce.store')}}">
                {{csrf_field()}}
                <div class="layui-tab" lay-filter="locale">
                    <ul class="layui-tab-title">
                        @foreach (config('app.supported_locales') as $key => $locale)
                            <li @if($loop->first)class="layui-this"@endif>{{$locale['name']}}</li>
                        @endforeach
                    </ul>
                    <div class="layui-tab-content">
                        @foreach (config('app.supported_locales') as $key => $locale)
                            <div
                                @if($loop->first)
                                 class="layui-tab-item layui-show"
                                @else
                                 class="layui-tab-item"
                                @endif>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">标题</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="value[{{$key}}][title]" value="{{$announce->getTranslation('value', $key)['title'] ?? ''}}"
                                               placeholder="请输入标题" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item upload" data-locale="{{$key}}">
                                    <label class="layui-form-label">封面图</label>
                                    <div class="layui-input-inline">
                                        <div class="layui-upload">
                                            <button type="button" class="layui-btn" id="covers_btn">
                                                上传封面图</button>
                                            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                                预览图：
                                                <div class="layui-upload-list" id="covers_image">
                                                    @foreach($announce->getTranslation('value', $key)['covers'] ?? [] as $image)
                                                        <div class="image-preview-box">
                                                            <img src="{{$image}}" class="layui-upload-img">
                                                            <input type="hidden" name="value[{{$key}}][covers][]"
                                                                   value="{{$image}}"/>
                                                            <button type="button"
                                                                    class="layui-btn layui-btn-danger layui-btn-xs covers_remove">删除
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">内容</label>
                                    <div class="layui-input-inline">
                                    <textarea type="text" name="value[{{$key}}][content]"
                                              placeholder="请输入公告内容" autocomplete="off" class="content layui-textarea">
                                        {{$announce->getTranslation('value', $key)['content'] ?? ''}}
                                    </textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="lay-announce">立即提交</button>
                        <a href="{{route('admin.announce.index')}}" class="layui-btn layui-btn-primary">返回</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('after-scripts')
    <script>
        layui.use(['form', 'table', 'layedit', 'element', 'upload'], function () {
            var $ = layui.$
                , layedit = layui.layedit
                , form = layui.form
                , table = layui.table
                , upload = layui.upload
                , element = layui.element;

            $(document).on('click', '.covers_remove', function () {
                $(this).parent().remove();
            });
            $('.upload').each(function() {
                console.log(1);
                var $this = $(this);
                const $btn =  $('#covers_btn', $this);
                const $images = $('#covers_image', $this);
                const locale = $this.data('locale');
                //多图片上传
                upload.render({
                    elem: $btn
                    , url: '{{route('admin.api.media.upload')}}'
                    , multiple: true
                    , done: function (res) {
                        if (res.message === undefined) {
                            //上传成功
                            $images.append('<div class="image-preview-box"><img src="' + res.url + '" class="layui-upload-img"><input type="hidden" name="value['+  locale +'][covers][]" value="' + res.path + '"/><button type="button" class="layui-btn layui-btn-danger layui-btn-xs covers_remove">删除</button></div>')
                        } else {
                            layer.msg('上传失败');
                        }
                    }
                });

            });

            $('textarea.content').each(function() {
                layedit.build(this, {
                    height: 320,
                    tool: [
                        'strong' //加粗
                        , 'italic' //斜体
                        , 'underline' //下划线
                        , 'del' //删除线
                        , '|' //分割线
                        , 'left' //左对齐
                        , 'center' //居中对齐
                        , 'right' //右对齐
                        , 'link' //超链接
                        , 'unlink' //清除链接
                        , 'face' //表情
                    ]
                })
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
