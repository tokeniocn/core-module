@extends('core::admin.layouts.app')

@section('body_class', 'layui-layout-body')
@section('wrapper_class', '')

@section('content')
    <layout-main>
        <template v-slot:toolbar-right>
            <q-btn-dropdown stretch flat label="{{ $logged_in_user->username }}">
                <q-list>
                    <q-item clickable v-ripple>
                        <q-item-section lay-event="edit_password">修改密码</q-item-section>
                    </q-item>
                    <q-item clickable v-ripple>
                        <q-item-section lay-event="logout">退出</q-item-section>
                    </q-item>
                </q-list>
            </q-btn-dropdown>
        </template>
    </layout-main>
@endsection

@push('after-scripts')
    <script>
        layui.use(['layer', 'util'], function() {
            var $ = layui.$;
            var util = layui.util;
            var events = {
                logout: function() {
                    layer.confirm('确定退出当前账号吗？', function () {
                        $.ajax({
                            url: '{{ route('admin.auth.logout') }}',
                            type: 'post',
                            success: function() {
                                window.location.href = '{{ route('admin.auth.login') }}'
                            }
                        });
                    });
                },


                edit_password:function () {
                    layer.prompt({title: '输入新登录密码，并确认', formType: 1}, function(password, index){
                        layer.close(index);
                        layer.prompt({title: '再次输入密码，并确认', formType: 1}, function(re_password, index){
                            layer.close(index);

                            if (password != re_password) {
                                layer.msg("两次输入的密码不一致");
                                return false;
                            }

                            layer.confirm('确定要修改当前登录管理员的登录密码？', function () {
                                $.ajax({
                                    url: '{{ route('admin.api.auth.edit_password') }}',
                                    type: 'post',
                                    data: {password:password},
                                    success: function() {
                                        layer.msg("修改成功，请牢记！");
                                    }
                                });
                            });

                        });
                    });
                }
            }
            util.event('lay-event', events);
        });
    </script>
@endpush


