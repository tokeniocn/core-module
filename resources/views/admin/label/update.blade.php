@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form method="post" class="layui-form">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label class="layui-form-label">Key</label>
                    <div class="layui-input-inline">
                        <input type="text" name="key" v-model="form.key"
                               placeholder="请输入Key" autocomplete="off" class="layui-input">
                        <div class="layui-form-mid layui-word-aux">参数最少要有两级，最多只能三级。比如system.name(两级)，system.share.remark(三级)，确定后无法修改</div>
                    </div>

                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">值</label>
                    <div class="layui-input-inline">
                        <div class="layui-tab" lay-filter="locale">
                            <ul class="layui-tab-title">
                                <li
                                    :class="{'layui-this': current_locale == index}"
                                    v-for="(locale, index) in supported_locales"
                                    :key="index"
                                    @click="current_locale = index"
                                    v-text="locale.name"></li>
                            </ul>
                            <div class="layui-tab-content" style="padding-left: 0; padding-right: 0">
                                <div
                                    v-for="(locale, index) in supported_locales" :key="index"
                                    :class="{'layui-tab-item': true, 'layui-show': current_locale == index}">
                                    <markdown-editor v-model="form.value[index]" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">label说明</label>
                    <div class="layui-input-inline">
                        <input type="text" name="remark" v-model="form.remark"
                               placeholder="请输入备注内容" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <button type="button" class="layui-btn" @click.stop="handleSubmit">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('before-scripts')
    @php
        $_supportedLocales = config('app.supported_locales');
        $_data = [
            'current_locale' => config('app.locale'),
            'url' => $label->exists ? route('admin.label.store', ['id' => $label->id]) : route('admin.label.store'),
            'form' => [
                'key' => $label->key,
                'remark' => $label->remark,
                'value' => collect($_supportedLocales)->map(function($locale, $key) use ($label) {
                    return $label->getTranslation('value', $key);
                })
            ],
            'supported_locales' => $_supportedLocales,
        ];
    @endphp

    <script>
        window.initOptions = {
            data() {
                return @json($_data, JSON_UNESCAPED_UNICODE);
            },
            methods: {
                async handleSubmit() {
                    const result = await this.$http.post(this.url, this.form);
                    await this.showNotify('提交成功');
                    window.parent.location.reload();
                }
            }
        };
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
