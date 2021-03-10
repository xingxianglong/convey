<!DOCTYPE html>
<html lang="zh-cn">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>{{$title ? $title : ''}}</title>
  <script>
    if (window != window.top) top.location.href = self.location.href;
  </script>
    <link rel="stylesheet" href="{{URL::asset('asset/layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{URL::asset('asset/font-awesome/css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{URL::asset('asset/login.css')}}">
    <link rel="stylesheet" href="{{URL::asset('asset/sideshow/css/normalize.css')}}">
    <link rel="stylesheet" href="{{URL::asset('asset/sideshow/css/component.css')}}">
  <!--[if IE]>
    <link rel="stylesheet" href="{{URL::asset('asset/sideshow/js/html5.js')}}">
  <![endif]-->
  <style>
    canvas {
      position: absolute;
      z-index: -1;
    }

    .kit-login-box header h1 {
      line-height: normal;
    }

    .kit-login-box header {
      height: auto;
    }

    .content {
      position: relative;
    }

    .codrops-demos {
      position: absolute;
      bottom: 0;
      left: 40%;
      z-index: 10;
    }

    .codrops-demos a {
      border: 2px solid rgba(242, 242, 242, 0.41);
      color: rgba(255, 255, 255, 0.51);
    }

    .kit-pull-right button,
    .kit-login-main .layui-form-item input {
      background-color: transparent;
      color: white;
    }

    .kit-login-main .layui-form-item input::-webkit-input-placeholder {
      color: white;
    }

    .kit-login-main .layui-form-item input:-moz-placeholder {
      color: white;
    }

    .kit-login-main .layui-form-item input::-moz-placeholder {
      color: white;
    }

    .kit-login-main .layui-form-item input:-ms-input-placeholder {
      color: white;
    }

    .kit-pull-right button:hover {
      border-color: #009688;
      color: #009688
    }
  </style>
</head>


<body class="kit-login-bg">
<div class="container demo-4">
  <div class="content">
    <div id="large-header" class="large-header">
      <canvas id="demo-canvas"></canvas>
      <div class="kit-login-box">
        <header>
          <h1>{{$title ? $title : ''}}</h1>
        </header>
        <div class="kit-login-main">
          <form class="layui-form" id="login">
            <div class="layui-form-item">
              <label class="kit-login-icon">
                <i class="layui-icon">&#xe612;</i>
              </label>
              <input type="text" name="login_account" autocomplete="off" placeholder="账号" class="layui-input" value="{{$login_account}}" style="border:1px solid rgba(0,150,136,.5);">
            </div>
            <div class="layui-form-item">
              <label class="kit-login-icon">
                <i class="layui-icon">&#xe642;</i>
              </label>
              <input type="password" name="login_password" autocomplete="off" placeholder="密码" class="layui-input" style="border:1px solid rgba(0,150,136,.5);">
            </div>
            <div class="layui-form-item">
              <div class="kit-pull-left kit-login-remember">
                <input type="checkbox" {{$login_account ? 'checked' : ''}} value="1" lay-skin="primary" title="记住帐号?" name="remember">
              </div>
              <div class="kit-pull-right">
                <button class="layui-btn layui-btn-primary" lay-submit lay-filter="login" style="border:1px solid rgba(0,150,136,.5);">
                  <i class="fa fa-sign-in" aria-hidden="true"></i> 登录
                </button>
              </div>
              <div class="kit-clear"></div>
            </div>

          </form>
        </div>
        <footer>
          <p>Xn © <a href="http://www.xxlzss.cn" style="color:white; font-size:18px;" target="_blank">www.xxlzss.cn</a></p>
        </footer>
      </div>
    </div>
  </div>
</div>
<!-- /container -->

<script src="{{URL::asset('asset/layui/layui.js')}}"></script>
<script src="{{URL::asset('asset/sideshow/js/TweenLite.min.js')}}"></script>
<script src="{{URL::asset('asset/sideshow/js/EasePack.min.js')}}"></script>
<script src="{{URL::asset('asset/sideshow/js/rAF.js')}}"></script>
<script src="{{URL::asset('asset/sideshow/js/demo-1.js')}}"></script>
<script>
  layui.use(['layer', 'form'], function() {
    var layer = layui.layer,
            $ = layui.jquery,
            form = layui.form;

    //提交
    form.on('submit(login)', function(data) {
      $.ajax({
        url:'{{"/Admin/bWQ1X0xvZ2luL0xvZ2luVmFsaWRhdGlvbl9tZDU="}}',
        type:'post',
        dataType:'json',
        data:{
          login_account : data.field.login_account ,
          login_password : data.field.login_password,
          remember : data.field.remember
        },
        success:function(res){
          if(res.code == 0){
            location.href='{{"/Admin/bWQ1X0luZGV4L0luZGV4X21kNQ=="}}';
            return true;
          }else{
            layer.msg(res.msg, {icon: 2});
          }
          return false;
        }
      })
      return false;
    });
  });
</script>
</body>

</html>
