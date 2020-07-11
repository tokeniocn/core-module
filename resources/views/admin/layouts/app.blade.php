@extends('core::admin.layouts.main')

@section('wrapper_class', 'layui-fluid')

@prepend('before-styles')
    {{ style(mix('css/layui.css')) }}
@endprepend

@prepend('after-scripts')
    {!! script(mix('js/layui.js')) !!}
@endprepend
