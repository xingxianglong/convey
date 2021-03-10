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
        .layui-card-header{font-size: 17px;border-left:10px solid #4fc3ff;}
        .text-row{display: flex;flex-wrap:wrap;justify-content: flex-start;margin-top: 20px;margin-bottom: 20px;}
        .text-title{flex:0 11%;font-size: 14px;}
        .text-content{flex:0 22%;}
        #max-img{position: fixed;top:0;left:0;z-index: 1000;width: 100%;
            height: 100%;text-align: center;display: none;background:rgba(0,0,0,0.5);}
    </style>
</head>
<body>
<div class="layui-tab">
    <ul class="layui-tab-title">
        <li class="layui-this">基础信息</li>
    </ul>
    <div class="layui-tab-content">
        <!-- 信息 begin -->
        <div class="layui-tab-item layui-show">
            <div class="layui-row layui-col-space10">
                <div class="layui-card">
                    <div class="layui-card-header">基础信息</div>
                    <div class="layui-card-body">
                        <div class="text-row">
                            <div class="text-title">用户姓名：</div>
                            <div class="text-content">{{$info->user_name ? $info->user_name : ''}}</div>

                            <div class="text-title">注册时间：</div>
                            <div class="text-content">{{$info->create_time ? $info->create_time : ''}}</div>
                        </div>

                        <div class="text-row">
                            <div class="text-title">头像：</div>
                            <div class="text-content" title="点击放大">
                                <img src="{{$info->head}}" alt="" class="img" style="width: 80px;height: 80px;border-radius: 50%;"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- 信息 end -->
    </div>
</div>

<!-- 放大的图片 begin -->
<div id="max-img" style="" title="点击关闭">
    <img src="" alt="" style="height: 100%;" />
</div>
<!-- 放大的图片 end -->


<script src="{{URL::asset("asset/layui/layui.js")}}"></script>
<script>
layui.use(['form', 'layedit', 'laydate','upload','element','table'], function(){
    var form = layui.form,
            layer = layui.layer,
            laydate = layui.laydate,
            upload = layui.upload,
            $ = layui.jquery,
            id = '{{isset($id) ? $id : ""}}';

    $('.img').click('on', function () {
        var that = $(this);
        var max_business_license = $('#max-img');

        max_business_license.fadeIn(500);
        max_business_license.children('img').attr('src',that.attr('src'));
    });
    $('#max-img').click('on', function () {
        var that = $(this);
        that.fadeOut(500);
    });

});
</script>

</body>
</html>
