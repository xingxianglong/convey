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
        <div class="layui-col-md12">
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>装修名称：</label>
                <div class="layui-input-block">
                    <input type="text" name="decorate_name" value="{{isset($info->decorate_name)?$info->decorate_name:''}}" autocomplete="off" placeholder="请输入装修名称" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">是否上架：</label>
                <div class="layui-input-block">
                    @if (isset($info))
                        <input type="radio" name="is_show" value="1" title="是" {{$info->is_show==1?'checked':''}}>
                        <input type="radio" name="is_show" value="2" title="否" {{$info->is_show==2?'checked':''}}>
                    @else
                        <input type="radio" name="is_show" value="1" title="是" checked>
                        <input type="radio" name="is_show" value="2" title="否" >
                    @endif
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">是否置顶：</label>
                <div class="layui-input-block">
                    @if (isset($info))
                        <input type="radio" name="is_top" value="1" title="是" {{$info->is_top==1?'checked':''}}>
                        <input type="radio" name="is_top" value="2" title="否" {{$info->is_top==2?'checked':''}}>
                    @else
                        <input type="radio" name="is_top" value="1" title="是">
                        <input type="radio" name="is_top" value="2" title="否" checked>
                    @endif
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">排序号：</label>
                <div class="layui-input-block">
                    <input type="text" name="sort" value="{{isset($info->sort)?$info->sort:'99'}}" autocomplete="off" placeholder="排序号" class="layui-input">
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

    //监听提交
    form.on('submit(demo1)', function(data){
        if(isSubmit == 1){
            layer.msg('你已提交，请勿重复点击',{icon:5,time:2000});
            return false;
        }

        var url = '';
        if(jump_type == 1)
        {
            url = '{{"/Admin/House/Decorate/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/House/Decorate/Edit"}}';
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
                decorate_name:data.field.decorate_name,
                is_show:data.field.is_show,
                is_top:data.field.is_top,
                sort:data.field.sort,
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
