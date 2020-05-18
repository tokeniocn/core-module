@extends('core::admin.layouts.app')

@section('content')
<div class="layui-card">
    <div class="layui-card-body">
        <form method="post" class="layui-form" action="{{route('admin.api.label.update')}}">
            {{csrf_field()}}
            @foreach($labelList as $label)
            <div class="layui-form-item">
                <label class="layui-form-label">{{$label['remark']}}</label>
                <div class="layui-input-inline">
                    <textarea type="text" name="{{$label['key']}}" value="{{$label['value']}}"
                           placeholder="请输入{{$label['remark']}}" autocomplete="off" class="layui-textarea">{{$label['value']}}</textarea>
                </div>

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
    layui.use(['upload', 'form'], function () {
        var $ = layui.jquery
            , upload = layui.upload;
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


