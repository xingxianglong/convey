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
                <label class="layui-form-label"><span style="color:red;">*</span>显示位置：</label>
                <div class="layui-input-block">
                    <select name="location_id">
                        <option value="">请选择显示位置</option>
                        @foreach ($location_data as $k => $v)
                            @if (isset($info))
                                <option value="{{$v->id}}" {{$v->id == $info->location_id?'selected':''}}>{{$v->location_name}}</option>
                            @else
                                <option value="{{$v->id}}">{{$v->location_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>图片：</label>
                <div class="layui-input-block">
                    <div class="upload-img">
                        <div class="sample-img">
                            <img src="{{isset($info->img) ? $info->img : ''}}" alt=""/>
                            <input type="hidden" name="img" value="{{isset($info->img) ? $info->img : ''}}" />
                            <input type="hidden" name="img_ext" value="{{isset($info->img_ext) ? $info->img_ext : ''}}" />
                            <input type="hidden" name="img_size" value="{{isset($info->img_size) ? $info->img_size : ''}}" />
                        </div>
                        <button type="button" class="layui-btn" id="img-img">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-col-md6">
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

    //上传图片
    var uploadImg = upload.render({
        elem: '#img-img' //绑定元素
        ,url: '{{"/Admin/bWQ1X1VwbG9hZC9JbWdfbWQ1"}}' //上传接口
        ,method : 'post'
        ,accept : 'file'
        ,exts : 'png|jpg|jpeg|gif'
        ,data: {
            file_path : 'advertising'
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
            url = '{{"/Admin/Advertising/Banner/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/Advertising/Banner/Edit"}}';
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
                location_id:data.field.location_id,
                img:data.field.img,
                img_ext:data.field.img_ext,
                img_size:data.field.img_size,
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
