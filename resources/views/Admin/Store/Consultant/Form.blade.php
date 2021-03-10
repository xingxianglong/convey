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
                <label class="layui-form-label"><span style="color:red">*</span>门店：</label>
                <div class="layui-input-block">
                    <button class="layui-btn" id="select-store">选择门店</button>
                    <input type="hidden" name="store_id" id="store-id" value="{{isset($info->store_id) ? $info->store_id : ''}}" />
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">门店信息：</label>
                <div class="layui-input-block">
                    <input type="text" name="store_info" id="store_info" value="{{isset($info->store_name) ? $info->store_name : ''}}{{isset($info->note) ? '('.$info->note.')' : ''}}" autocomplete="off" placeholder="请选择门店" class="layui-input" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red">*</span>用户：</label>
                <div class="layui-input-block">
                    <button class="layui-btn" id="select-user">选择用户</button>
                    <input type="hidden" name="user_id" id="user-id" value="{{isset($info->user_id) ? $info->user_id : ''}}" />
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">用户信息：</label>
                <div class="layui-input-block">
                    <input type="text" name="user_info" id="user_info" value="{{isset($info->user_name) ? $info->user_name : ''}}" autocomplete="off" placeholder="请选择用户" class="layui-input" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>顾问姓名：</label>
                <div class="layui-input-block">
                    <input type="text" name="consultant_name" value="{{isset($info->consultant_name)?$info->consultant_name:''}}" autocomplete="off" placeholder="请输入顾问姓名" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>职位：</label>
                <div class="layui-input-block">
                    <select name="position_id">
                        <option value="">请选择职位</option>
                        @foreach ($position_data as $k => $v)
                            @if (isset($info))
                                <option value="{{$v->id}}" {{$v->id == $info->position_id?'selected':''}}>{{$v->position_name}}</option>
                            @else
                                <option value="{{$v->id}}">{{$v->position_name}}</option>
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
        </div>

        <div class="layui-col-md6">
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>入职日期：</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" value="{{isset($info->induction_date)?$info->induction_date:''}}" autocomplete="off" placeholder="请选择入职日期" name="induction_date" id="induction_date">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>性别：</label>
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
                <label class="layui-form-label"><span style="color:red;">*</span>头像：</label>
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

    //选择门店
    $('#select-store').on('click',function(){
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '选择门店'
            ,area: ['90%', '90%']
            ,shade: 0.3
            ,maxmin: true
            ,shadeClose: false //点击遮罩关闭层
            ,closeBtn:1
            ,content: '{{"/Admin/Store/Store/Select/1"}}'
            ,zIndex: layer.zIndex //重点1
            ,btn:['选择','取消']
            ,yes: function(index, layero){
                var body = layer.getChildFrame('body',index);

                var iframeWin = window[layero.find('iframe')[0]['name']];

                var table_checkbox = iframeWin.layui.table.checkStatus('demo');

                if(table_checkbox['data'].length <= 0){
                    layer.msg('请选择门店',{icon:2,time:2000});
                }

                if(isNaN(parseInt(table_checkbox['data'][0]['id']))){
                    layer.msg('找不到门店id',{icon:2,time:2000});
                }
                var store_id = table_checkbox['data'][0]['id'];

                $.ajax({
                    url:'{{"/Admin/Store/Store/GetInfo/"}}'+store_id,
                    type:'get',
                    dataType:'json',
                    data:{

                    },success:function(res){
                        if(res.code == 0 ){
                            var store_id = $('input[name="store_id"]');
                            var store_info = $('input[name="store_info"]');

                            store_id.val(res.data.id);

                            var store_info_str = '';
                            store_info_str += res.data.store_name;
                            if(res.data.note != '')
                            {
                                store_info_str += '(' + res.data.note + ')';
                            }
                            store_info.val(store_info_str);

                            layer.close(layer.index);
                        }else{
                            layer.msg(res.msg,{icon:2,time:2000});
                        }
                    },fail:function(error){
                        console.log(error);
                    }
                });
            }
        });
        return false;
    });


    //选择用户
    $('#select-user').on('click',function(){
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '选择用户'
            ,area: ['90%', '90%']
            ,shade: 0.3
            ,maxmin: true
            ,shadeClose: false //点击遮罩关闭层
            ,closeBtn:1
            ,content: '{{"/Admin/User/User/Select/1"}}'
            ,zIndex: layer.zIndex //重点1
            ,btn:['选择','取消']
            ,yes: function(index, layero){
                var body = layer.getChildFrame('body',index);

                var iframeWin = window[layero.find('iframe')[0]['name']];

                var table_checkbox = iframeWin.layui.table.checkStatus('demo');

                if(table_checkbox['data'].length <= 0){
                    layer.msg('请选择用户',{icon:2,time:2000});
                }

                if(isNaN(parseInt(table_checkbox['data'][0]['id']))){
                    layer.msg('找不到用户id',{icon:2,time:2000});
                }
                var user_id = table_checkbox['data'][0]['id'];

                $.ajax({
                    url:'{{"/Admin/User/User/GetInfo/"}}'+user_id,
                    type:'get',
                    dataType:'json',
                    data:{

                    },success:function(res){
                        if(res.code == 0 ){
                            var user_id = $('input[name="user_id"]');
                            var user_info = $('input[name="user_info"]');

                            user_id.val(res.data.id);

                            var user_info_str = res.data.user_name;
                            user_info.val(user_info_str);

                            layer.close(layer.index);
                        }else{
                            layer.msg(res.msg,{icon:2,time:2000});
                        }
                    },fail:function(error){
                        console.log(error);
                    }
                });
            }
        });
        return false;
    });

    //入职日期
    laydate.render({
        elem: '#induction_date' //指定元素
    });

    //上传图片
    var uploadImg = upload.render({
        elem: '#head-img' //绑定元素
        ,url: '{{"/Admin/bWQ1X1VwbG9hZC9JbWdfbWQ1"}}' //上传接口
        ,method : 'post'
        ,accept : 'file'
        ,exts : 'png|jpg|jpeg|gif'
        ,data: {
            file_path : 'store_consultant'
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
            url = '{{"/Admin/Store/Consultant/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/Store/Consultant/Edit"}}';
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
                store_id:data.field.store_id,
                user_id:data.field.user_id,
                consultant_name:data.field.consultant_name,
                position_id:data.field.position_id,
                phone:data.field.phone,
                sex:data.field.sex,
                induction_date:data.field.induction_date,
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
