<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{URL::asset("asset/layui/css/layui.css")}}">
    <style>
        #submit .layui-input-block{margin:0;}
        #submit{position: fixed;bottom: 0;background-color: #dedede;margin: 0;width: 100%;text-align:center;}
        .hide{display: none}
        .upload-img{margin: 0 0 0 20px;}
        .sample-img{width:120px;height: 120px;border:1px solid #dedede;}
        .sample-img > img{width: 100%;height: 100%;}
        .layui-btn{width: 122px;}
    </style>
</head>
<body>
<form class="layui-form" style="padding: 10px 10px 50px 0;">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md12">
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>登录账号：</label>
                <div class="layui-input-block">
                    <input type="text" name="login_account" id="login_account" autocomplete="off" value="{{$info->login_account ? $info->login_account : ''}}" placeholder="请输入登录账号" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>登录密码：</label>
                <div class="layui-input-block">
                    <input type="password" name="login_password" id="login_password" autocomplete="off" placeholder="请输入登录密码" class="layui-input">
                </div>
            </div>
        </div>
    </div>

    <div class="layui-form-item" id="submit">
        <div class="layui-input-block">
            <button id="btn" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
        </div>
    </div>
</form>

<script src="{{URL::asset("asset/layui/layui.js")}}"></script>
<script>
layui.use(['form'], function(){
    var form = layui.form,
            $ = layui.jquery,
            isSubmit = 0, //是否已提交
            id = '{{$id ? $id : ""}}';

    //监听提交
    form.on('submit(demo1)', function(data){
        if(isSubmit == 1){
            layer.msg('你已提交，请勿重复点击',{icon:5,time:2000});
            return false;
        }
        var url = '{{"/Admin/System/Administrator/EditAccount"}}';

        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data:{
                id:id,
                login_account:data.field.login_account,
                login_password:data.field.login_password
            },success:function(res){
                if(res.code == 0 ){
                    isSubmit = 1;
                    layer.msg(res.msg,{time:1000},function () {
                        window.parent.location.reload();
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(index); //再执行关闭
                    });
                }else{
                    layer.msg(res.msg,{icon:2,time:2000});
                }
            },fail:function(error){
                console.log(error);
            }
        });
        return false;
    });

});
</script>

</body>
</html>
