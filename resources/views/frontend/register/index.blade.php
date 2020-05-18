
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>注册</title>
    <style>
        @font-face {
            font-family: "cloud-iconfont";
            font-style: normal;
            font-weight: normal;
            src: url("https://at.alicdn.com/t/font_1449502_4mghzcnsdwc.ttf") format("truetype");
        }

        .cloud-icons {
            font-family: "cloud-iconfont";
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .register {
            width: 100%;
            height: 100%;
        }

        .register-title {
            width: 100%;
            height: 20%;
            margin-top: 20px;
        }

        .ml {
            margin-left: 20px;
        }

        .title {
            color: #FFC134;
            font-size: 24px;
        }

        .content {
            margin-top: 10px;
            color: #333333;
            font-size: 15px;
        }

        .form {
            margin-top: 32px;
        }

        .form-title {
            color: #000;
            font-size: 19px;
            font-weight: 500;
        }

        .username {
            position: relative;
            width: 89%;
            height: 50px;
            border: 1px solid #DDDDDD;
            margin-top: 20px;
            border-radius: 10px;
        }

        .verify {
            position: relative;
            width: 62%;
            height: 50px;
            border: 1px solid #DDDDDD;

            border-radius: 10px;
        }

        .verify-btn {
            /* position: absolute;
            right: 17px;
            top: 231px; */
            width: 25%;
            height: 50px;
            line-height: 50px;
            background: #FFC43A;
            border-radius: 10px;
            text-align: center;
        }

        .icon {
            position: absolute;
            width: 10%;
            height: 100%;
            line-height: 50px;
            text-align: center;
            font-size: 20px;
            color: #999999;
            /* background-color: #000000; */
        }

        .username input,
        .verify input {
            position: absolute;
            left: 10%;
            height: 100%;
            width: 90%;
            border: none;
            border-radius: 10px;
            /* color: #bbbbbb; */
            font-size: 14px;
        }

        .submit-button {
            height: 170px;
        }

        .submit {
            margin-top: 40px;
            width: 94%;
            height: 50px;
            line-height: 50px;
            text-align: center;
            border-radius: 10px;
            font-size: 18px;
            background: linear-gradient(to right, #FFD063, #FFC134);
        }

        .advertisement {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 80px;
            z-index: 99;
            background-color: #666561;
        }

        .advertisement-img {
            width: 41px;
            height: 41px;
            margin-top: 22px;
            margin-left: 26px;
        }

        .advertisement-content {
            position: absolute;
            top: 20px;
            left: 80px;
            color: #fff;
        }

        .advertisement-btn {
            position: absolute;
            right: 35px;
            top: 20px;
            width: 100px;
            text-align: center;
            height: 40px;
            line-height: 40px;
            background: #FFC135;
            border-radius: 20px;
        }

        .close {
            position: absolute;
            top: 4px;
            right: 5px;
            width: 14px;
            height: 14px;
        }
    </style>
</head>

<body>
<div class="register">
    <div class="register-title">
        <div class="title ml">用户注册</div>
        <div class="content ml">Cloud Wallet.</div>
    </div>

    <div class="form">
        <div class="form-title ml">注册</div>
        <div class="username ml">
            <i class="cloud-icons icon">&#xe655;</i>
            <input type="text" placeholder="请输入手机号" id="mobile" name="mobile" value="">
        </div>
        <div class="ml" style="margin-top: 20px;display: flex;width: 89%;justify-content: space-between;">
            <div class="verify">
                <i class="cloud-icons icon" style="width:15.3%">&#xe6bf;</i>
                <input style="width: 84.7%;left: 15.3%;" placeholder="短信验证码" type="number" id="text" name="sms" value="">
            </div>
            <div class="verify-btn get-code" onclick="sendSms()" style="margin-left: 1rem; padding:0 10px">获取验证码</div>
        </div>
        <div class="username  ml">
            <i class="cloud-icons icon">&#xe777;</i>
            <input placeholder="请输入登录密码" type="password" id="password" name="password" value="">
        </div>

        <div class="username  ml">
            <i class="cloud-icons icon">&#xe777;</i>
            <input placeholder="请确认登录密码" type="password" id="repassword" value="">
        </div>

        <div class="username  ml">
            <view class="cloud-icons icon">&#xe693;</view>
            <input placeholder="请输入邀请码" id="code" name="code" value="">
        </div>


        <div class="submit-button ml">
            <div class="submit" id="btn-submit">注册</div>
        </div>


    </div>
</div>

<div class="advertisement" id="advertisements">
    <div class="advertisement-img">
        <img style="width:100%" src="./images/logo.png" alt="">
    </div>
    <div class="advertisement-content">
        <div style="font-size:15px">Cloud Wallet</div>
        <div style="font-size:12px;margin-top:5px;">Cloud Wallet Description</div>
    </div>
    <div class="advertisement-btn">
        <a style="text-decoration:none;color:#474747" href="{$ios}">下载</a>
    </div>
    <div class="close" onclick="closeAd()">
        <img style="width:100%" src="./images/close.png" alt="">
    </div>
</div>
</body>

<script src="https://cdn.bootcdn.net/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/layer/1.8.5/layer.min.js"></script>
<script>
    var host = window.location.origin;
    $('#btn-submit').click(function () {
        var mobile = $("input[name='mobile']").val();
        var password = $("input[name='password']").val();
        var repassword = $("#repassword").val();
        var code = $("input[name='code']").val();
        var sms = $("input[name='sms']").val();

        if (!(mobile && code && password && sms)) {
            layer.msg("请填写完整信息");
            return false;
        }
        if (password != repassword) {
            layer.msg("确认登录密码不相同");
            return false;
        }
        var reg = /^(?=.*\d)(?=.*[a-zA-Z]).{6,20}$/;
        if (!reg.test(password)) {
            layer.msg("登录密码为6-20位字母数字组合");
            return false;
        }
        $.post(host + '/api/v1/mobile_register', {
            mobile: mobile,
            password: password,
            invite_code: code, //邀请码
            code: sms, // 验证码
        }).done(function (data) {

            layer.msg('注册成功');


        }).fail(function (res) {
            layer.msg(res.responseJSON.message)
        })
    });
    var check = true;

    function sendSms() {
        var mobile = $("input[name='mobile']").val();
        if (mobile == '') {
            layer.msg('请先输入手机号码');
            return false;
        }
        if (!isValidPhone(mobile)) {
            return;
        }
        if (check == false) {
            return;
        }
        $.get(host + '/api/v1/mobile_register', {
            mobile: mobile
        }, function (res) {
            layer.msg('已发送')
            time()
        })
            .fail(function (json) {
                layer.msg(json.responseJSON.message)
            })
    }


    function isValidPhone(phone) {

        if (!/^1[3456789]\d{9}$/.test(phone)) {
            layer.msg('请输入正确手机号')
            return false;
        }
        return true;
    }
    // 倒计时
    function time() {
        var that = $('.get-code');
        var timeo = 60;
        var timeStop = setInterval(function () {
            timeo--;
            if (timeo > 0) {
                that.text(timeo + 's');
                that.attr('disabled', 'disabled'); //禁止点击
                check = false;
            } else {
                timeo = 60; //当减到0时赋值为60
                that.text('获取验证码');
                clearInterval(timeStop); //清除定时器
                that.removeAttr('disabled'); //移除属性，可点击
                check = true;
            }
        }, 1000)
    }

    function closeAd() {
        let advertisement = document.getElementById('advertisements')
        advertisement.style.display = "none"
    }
</script>

</html>
