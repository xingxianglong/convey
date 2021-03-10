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
                <label class="layui-form-label"><span style="color:red;">*</span>门店名称：</label>
                <div class="layui-input-block">
                    <input type="text" name="store_name" value="{{isset($info->store_name)?$info->store_name:''}}" autocomplete="off" placeholder="请输入门店名称" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">备注：</label>
                <div class="layui-input-block">
                    <input type="text" name="note" id="note" value="{{isset($info->note)?$info->note:''}}" autocomplete="off" placeholder="备注" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>封面图：</label>
                <div class="layui-input-block">
                    <div class="upload-img">
                        <div class="sample-img">
                            <img src="{{isset($info->cover) ? $info->cover : ''}}" alt=""/>
                            <input type="hidden" name="cover" value="{{isset($info->cover) ? $info->cover : ''}}" />
                            <input type="hidden" name="cover_ext" value="{{isset($info->cover_ext) ? $info->cover_ext : ''}}" />
                            <input type="hidden" name="cover_size" value="{{isset($info->cover_size) ? $info->cover_size : ''}}" />
                        </div>
                        <button type="button" class="layui-btn" id="cover-img">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-col-md6">
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>省份：</label>
                <div class="layui-input-block">
                    <select name="province_id" id="province" lay-filter="select_province">
                        <option value="">请选择省份</option>
                        @foreach ($province_data as $k => $v)
                            @if (isset($info))
                                <option value="{{$v->id}}" {{$v->id==$info->province_id ? 'selected' : ''}}>{{$v->province_name}}</option>
                            @else
                                <option value="{{$v->id}}">{{$v->province_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>城市：</label>
                <div class="layui-input-block">
                    <select name="city_id" id="city" lay-filter="select_city">
                        <option value="">请先选择省份</option>
                        @if (isset($info))
                            @foreach ($city_data as $k => $v)
                                <option value="{{$v->id}}" {{$v->id==$info->city_id ? 'selected' : ''}}>{{$v->city_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>区域：</label>
                <div class="layui-input-block">
                    <select name="district_id" id="district" lay-filter="select_district">
                        <option value="">请先选择省份</option>
                        @if (isset($info))
                            @foreach ($district_data as $k => $v)
                                <option value="{{$v->id}}" {{$v->id==$info->district_id ? 'selected' : ''}}>{{$v->district_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>详细地址：</label>
                <div class="layui-input-block">
                    <textarea name="detail_address" placeholder="请输入详细地址" class="layui-textarea" style="resize: none;">{{isset($info->detail_address) ? $info->detail_address : ''}}</textarea>
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
        elem: '#cover-img' //绑定元素
        ,url: '{{"/Admin/bWQ1X1VwbG9hZC9JbWdfbWQ1"}}' //上传接口
        ,method : 'post'
        ,accept : 'file'
        ,exts : 'png|jpg|jpeg|gif'
        ,data: {
            file_path : 'store'
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

    //选择省份，加载城市
    form.on('select(select_province)', function(data){
        var province_id = data.value;

        var city = $("#city");
        var district = $("#district");
        //清空
        city.empty();
        district.empty();

        var html = '<option value="">请选择城市</option>';
        city.append(html);
        var html = '<option value="">请先选择城市</option>';
        district.append(html);

        form.render('select'); //刷新select选择框渲染

        $.ajax({
            url:'{{"/Admin/bWQ1X1N5c3RlbS9DaXR5L0FjY29yZGluZ1Byb3ZpbmNlR2V0RGF0YV9tZDU="}}',
            type:'post',
            dataType:'json',
            data:{
                province_id : province_id
            },success:function(res){
                if(res.code == 1)
                {
                    layer.msg(res.msg,{icon:2,time:2000});
                    return false;
                }
                var html = '';
                res.data.forEach(function (item){
                    html += '<option value="'+ item.id +'">'+ item.city_name +'</option>';
                });

                city.append(html);
                form.render('select'); //刷新select选择框渲染

            },fail:function(error){
                console.log(error);
            }
        });
    });


    //选择城市，加载区域
    form.on('select(select_city)', function(data){
        var city_id = data.value;

        var district = $("#district");
        //清空
        district.empty();

        var html = '<option value="">请选择区域</option>';
        district.append(html);
        form.render('select'); //刷新select选择框渲染

        $.ajax({
            url:'{{"/Admin/bWQ1X1N5c3RlbS9EaXN0cmljdC9BY2NvcmRpbmdDaXR5R2V0RGF0YV9tZDU="}}',
            type:'post',
            dataType:'json',
            data:{
                city_id : city_id
            },success:function(res){
                if(res.code == 1)
                {
                    layer.msg(res.msg,{icon:2,time:2000});
                    return false;
                }
                var html = '';
                res.data.forEach(function (item){
                    html += '<option value="'+ item.id +'">'+ item.district_name +'</option>';
                });

                district.append(html);
                form.render('select'); //刷新select选择框渲染

            },fail:function(error){
                console.log(error);
            }
        });
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
            url = '{{"/Admin/Store/Store/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/Store/Store/Edit"}}';
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
                store_name:data.field.store_name,
                note:data.field.note,
                cover:data.field.cover,
                cover_ext:data.field.cover_ext,
                cover_size:data.field.cover_size,
                province_id:data.field.province_id,
                city_id:data.field.city_id,
                district_id:data.field.district_id,
                detail_address:data.field.detail_address,
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
