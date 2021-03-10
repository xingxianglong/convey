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
        .layui-form-label{width: 90px;}
        .layui-input-block{margin-left:120px;}
        .sample-img{width:200px;height: 200px;border:1px solid #dedede;}
        .sample-img > img{width: 100%;height: 100%;}
        .upload-img > .layui-btn{width: 200px;}
    </style>
</head>
<body>
<form class="layui-form" style="padding: 10px 10px 50px 0;">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md6">
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>管理员名称：</label>
                <div class="layui-input-block">
                    <input type="text" name="administrator_name" value="{{isset($info->administrator_name)?$info->administrator_name:''}}" autocomplete="off" placeholder="请输入管理员名称" class="layui-input">
                </div>
            </div>

            @if (!isset($info))
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>登陆账号：</label>
                <div class="layui-input-block">
                    <input type="text" name="login_account" value="" autocomplete="off" placeholder="请输入登陆账号" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>登录密码：</label>
                <div class="layui-input-block">
                    <input type="password" name="login_password" autocomplete="off" placeholder="请输入登录密码" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>确认密码：</label>
                <div class="layui-input-block">
                    <input type="password" name="confirm_password" autocomplete="off" placeholder="请输入确认密码" class="layui-input">
                </div>
            </div>
            @endif

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>角色：</label>
                <div class="layui-input-block">
                    <select name="role_id">
                        <option value="">请选择管理员角色</option>
                        @foreach ($role_data as $k => $v)
                            @if (isset($info))
                                <option value="{{$v->id}}" {{$v->id == $info->role_id?'selected':''}}>{{$v->role_name}}</option>
                            @else
                                <option value="{{$v->id}}">{{$v->role_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>手机号码：</label>
                <div class="layui-input-block">
                    <input type="text" name="phone" id="phone" value="{{isset($info->phone)?$info->phone:''}}" autocomplete="off" placeholder="请输入手机号码" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>电子邮箱：</label>
                <div class="layui-input-block">
                    <input type="text" name="email" id="email" value="{{isset($info->email)?$info->email:''}}" autocomplete="off" placeholder="请输入电子邮箱" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-col-md6">
            <div class="layui-form-item">
                <label class="layui-form-label">出生日期：</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" value="{{isset($info->birthday)?$info->birthday:''}}" autocomplete="off" name="birthday" id="birthday">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">性别：</label>
                <div class="layui-input-block">
                    @if (isset($info))
                        <input type="radio" name="sex" value="1" title="男" {{$info->sex==1?'checked':''}}>
                        <input type="radio" name="sex" value="2" title="女" {{$info->sex==2?'checked':''}}>
                    @else
                        <input type="radio" name="sex" value="1" title="男" checked>
                        <input type="radio" name="sex" value="2" title="女" >
                    @endif
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">管理员头像：</label>
                <div class="layui-input-block">
                    <div class="upload-img">
                        <div class="sample-img">
                            <img src="{{isset($info->head) ? $info->head : ''}}" alt=""/>
                            <input type="hidden" name="head" value="{{isset($info->head) ? $info->head : ''}}" />
                            <input type="hidden" name="head_ext" value="{{isset($info->head_ext) ? $info->head_ext : ''}}" />
                            <input type="hidden" name="head_size" value="{{isset($info->head_size) ? $info->head_size : ''}}" />
                        </div>
                        <button type="button" class="layui-btn" id="head-img">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-form-item" id="submit">
        <div class="layui-input-block">
            <button id="btn" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
            <button id="button-cancel" type="button" class="layui-btn layui-btn-normal">取消</button>
        </div>
    </div>
</form>

<script src="{{URL::asset("asset/layui/layui.js")}}"></script>
<script>
layui.use(['form', 'layedit', 'laydate','upload','element','table'], function(){
    var form = layui.form,
            layer = layui.layer,
            laydate = layui.laydate,
            upload = layui.upload,
            $ = layui.jquery,
            isSubmit = 0, //是否已提交
            id = '{{isset($id) ? $id : ""}}',
            jump_type = '{{$jump_type}}';

    //出生日期
    laydate.render({
        elem: '#birthday' //指定元素
    });

    //上传图片
    var uploadImg = upload.render({
        elem: '#head-img' //绑定元素
        ,url: '{{"/Admin/bWQ1X1VwbG9hZC9JbWdfbWQ1"}}' //上传接口
        ,method : 'post'
        ,accept : 'file'
        ,exts : 'png|jpg|jpeg|gif'
        ,data: {
            file_path : 'administrator_head'
        }
        ,done: function(res){
            if(res.code == 1){
                layer.msg(res.msg,{icon:2,time:2000});
                return false;
            }
            //上传完毕回调
            var sample_img = $('.sample-img');
            sample_img.children('img').prop('src',res.data.src);
            sample_img.children('input').eq(0).val(res.data.src);
            sample_img.children('input').eq(1).val(res.data.ext);
            sample_img.children('input').eq(2).val(res.data.size);
            layer.msg(res.msg,{icon:1,time:2000});
        }
        ,error: function(error){
            //请求异常回调
            layer.msg(error,{icon:2,time:2000});
        }
    });

    //监听提交
    form.on('submit(demo1)', function(data){
        if(isSubmit == 1){
            layer.msg('你已提交，请勿重复点击',{icon:5,time:2000});
            return false;
        }

        var url = '';
        if(jump_type == 1)
        {
            url = '{{"/Admin/System/Administrator/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/System/Administrator/Edit"}}';
        }
        else
        {
            layer.msg('跳转参数错误',{icon:2,time:2000});
        }

        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data:{
                id:id,
                administrator_name:data.field.administrator_name,
                login_account:data.field.login_account,
                login_password:data.field.login_password,
                confirm_password:data.field.confirm_password,
                role_id:data.field.role_id,
                phone:data.field.phone,
                email:data.field.email,
                birthday:data.field.birthday,
                sex:data.field.sex,
                head:data.field.head,
                head_ext:data.field.head_ext,
                head_size:data.field.head_size,
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

    //取消
    $('#button-cancel').on('click',function(){
//        window.parent.location.reload();
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭
        return false;
    });

});
</script>

</body>
</html>
