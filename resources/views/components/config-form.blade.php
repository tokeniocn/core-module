@foreach($configList as $config)
    <div class="layui-form-item">
        <label class="layui-form-label">{{$config['title']}}</label>
        <div class="layui-input-inline">
            @if($config['type'] === 'image')
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="{{$config['key']}}_btn">
                        上传{{$config['title']}}</button>
                    <div class="layui-upload-list">
                        @if($config['value'] ==='')
                            <img src="" class="layui-upload-img" id="{{$config['key']}}_image">
                        @else
                            <img src="{{$config['value']}}" class="layui-upload-img"
                                 id="{{$config['key']}}_image">
                        @endif
                        <p id="{{$config['key']}}_text"></p>
                    </div>
                </div>
                <input type="hidden" name="{{$config['key']}}" value="{{$config['value']}}"
                       placeholder="请输入{{$config['title']}}" autocomplete="off" class="layui-input">
            @elseif($config['type'] === 'image_list')
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="{{$config['key']}}_btn">
                        上传{{$config['title']}}</button>
                    <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                        预览图：
                        <div class="layui-upload-list" id="{{$config['key']}}_image">
                            @foreach($config['value'] as $image)
                                <div class="image-preview-box">
                                    <img src="{{$image}}" class="layui-upload-img">
                                    <input type="hidden" name="{{$config['key']}}[]"
                                           value="{{$image}}"/>
                                    <button type="button"
                                            class="layui-btn layui-btn-danger layui-btn-xs">删除
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </blockquote>
                </div>
            @elseif($config['type'] === 'radio')
                @foreach($config['data'] as $key)
                    <input type="radio" name="{{$config['key']}}" name="{{$key}}" value="{{$key}}" title="{{$key}}"
                           @if($config['value'] == $key)
                           checked
                            @endif
                    />
                @endforeach
            @elseif($config['type'] === 'checkbox')
                @foreach($config['data'] as $key)
                    <input type="checkbox" name="{{$config['key']}}[]" value="{{$key}}" lay-skin="primary"
                           title="{{$key}}"
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
