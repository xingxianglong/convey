<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href='{{URL::asset("asset/layui/css/layui.css")}}'>
    <style>
        #submit .layui-input-block{margin:0;}
        #submit{position: fixed;bottom: 0;background-color: #dedede;margin: 0;width: 100%;text-align:center;}
        .hide{display: none}
        #demo2{display: flex;align-items: center;flex-wrap:wrap;}
        #demo2 img{width: 200px;height: 200px;}
        .img-block{margin: 0 10px 10px 0;position: relative;}
        .img-delete{position: absolute;top:0;right: 0;}
    </style>
</head>
<body>
<form class="layui-form" style="padding: 10px 10px 50px 0;">
    <div class="layui-form-item">
        <label class="layui-form-label"><span style="color:red;">*</span>键名：</label>
        <div class="layui-input-block">
            <input type="text" name="_key" id="_key" value="{{isset($info->_key)?$info->_key:''}}" autocomplete="off" placeholder="请输入键名" class="layui-input" @if (isset($info)) disabled @endif>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">值：</label>
        <div class="layui-input-block">
            <textarea name="_value" placeholder="请输入值" class="layui-textarea" style="resize:none;">{{isset($info->_value)?$info->_value:''}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">图片：</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test2">多图片上传</button>
                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;max-width: 1050px;">
                    预览图：
                    <div class="layui-upload-list" id="demo2">
                        @if (isset($info))
                        @foreach ($info->img as $k => $v)
                        @if (isset($v) && !empty($v))
                        <div class="img-block">
                            <img src="{{$v}}" class="layui-upload-img">
                            <input type="hidden" name="img[]" class="img" value="{{$v}}" />
                            <div class="img-delete">
                                <button class="layui-btn layui-btn-danger layui-btn-sm delete-attribute">
                                    <i class="layui-icon">&#xe640;</i>
                                </button>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </blockquote>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">备注说明：</label>
        <div class="layui-input-block">
            <textarea name="note" placeholder="备注说明" class="layui-textarea" style="resize:none;">{{isset($info->note)?$info->note:''}}</textarea>
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
            $ = layui.jquery,
            upload = layui.upload,
            isSubmit = 0, //是否已提交
            id = '{{isset($id) ? $id : ""}}',
            jump_type = '{{$jump_type}}';

    //上传图片
    var uploadImg = upload.render({
        elem: '#test2' //绑定元素
        ,url: '{{"/Admin/bWQ1X1VwbG9hZC9JbWdfbWQ1"}}' //上传接口
        ,method : 'post'
        ,multiple: true
        ,accept : 'file'
        ,exts : 'png|jpg|jpeg|gif'
        ,data: {
            file_path : 'system_config'
        }
        ,done: function(res){
            if(res.code == 1){
                layer.msg(res.msg,{icon:2,time:2000});
                return false;
            }
            //上传完毕回调
            var html = '';

            html += '<div class="img-block">';
            html += '<img src="'+ res.data.src +'" class="layui-upload-img">';
            html += '<input type="hidden" name="img[]" class="img" value="'+ res.data.src +'" />';
            html += '<div class="img-delete">';
            html += '<button class="layui-btn layui-btn-danger layui-btn-sm delete-attribute">';
            html += '<i class="layui-icon">&#xe640;</i>';
            html += '</button>';
            html += '</div>';
            html += '</div>';

            $('#demo2').append(html);
            layer.msg(res.msg,{icon:1,time:2000});

            $('.img-delete').on('click',function(){
                var that = $(this);
                $.windowBox.img_delete(that);
            });
        }
        ,error: function(error){
            //请求异常回调
            layer.msg(error,{icon:2,time:2000});
        }
    });

    $('.img-delete').on('click',function(){
        var that = $(this);
        $.windowBox.img_delete(that);
    });

    $.windowBox = {
        //图片删除
        img_delete:function(obj){
            obj.parent().remove();
            return false;
        }
    };

    //监听提交
    form.on('submit(demo1)', function(data){
        if(isSubmit == 1){
            layer.msg('你已提交，请勿重复点击',{icon:5,time:2000});
            return false;
        }

        var url = '';
        if(jump_type == 1)
        {
            url = '{{"/Admin/System/Config/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/System/Config/Edit"}}';
        }
        else
        {
            layer.msg('跳转参数错误',{icon:2,time:2000});
        }

        var img_arr = [];
        var img = $('.img');

        if(img.length > 0){
            for(var i=0;i<img.length;i++){
                img_arr.push(img.eq(i).val());
            }
        }


        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data:{
                _key:data.field._key,
                _value:data.field._value,
                img:img_arr,
                note:data.field.note,
                id:id
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
