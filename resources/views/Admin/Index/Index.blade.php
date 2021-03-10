<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{$title ? $title : ''}}</title>
    <link rel="stylesheet" href="{{URL::asset("asset/layui/css/layui.css")}}">
    <style>
        #prompt{position: fixed;bottom:30px;right: 15px;z-index: 9999;width: 240px;
            border:1px solid #dedede;display: none;font-size: 14px;}
        #prompt--header{display: flex;justify-content:space-between;}
        .prompt-text a{color:red;}
    </style>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo" >
{{--            <img src="/static/system_img/logo.png" alt="logo" style="width: 75%;height:75%;" />--}}
        </div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <!--<ul class="layui-nav layui-layout-left">-->
        <!--{volist name="one_menu_data" id="vo" key="k"}-->
        <!--<li class="layui-nav-item {$k==1 ? 'layui-this layui-nav-itemed' : ''}">-->
        <!--<a href="javascript:void(0);" class="one_menu" data-id="{$vo.id}">{$vo.menu_name}</a>-->
        <!--</li>-->
        <!--{/volist}-->
        <!--</ul>-->
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="{{$administrator_info['head'] ? $administrator_info['head'] : URL::asset('asset/system_img/default_user_head.jpg')}}" class="layui-nav-img">
                    {{$administrator_info['administrator_name'] ? $administrator_info['administrator_name'] : ''}}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:void(0);" id="edit-user-info">修改信息</a></dd>
                    <dd><a href="javascript:void(0);" id="edit-account">重置账号</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="" id="out">退出</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree two-menu">
                @foreach ($one_menu_data as $k => $v)
                    <li class="layui-nav-item {{$k==0 ? 'layui-nav-itemed' : ''}}">
                        <a class="two-menu-li" href="javascript:;">{{$v->menu_name}}</a>
                        <dl class="layui-nav-child">
                            @foreach ($v->two_data as $k2 => $v2)
                            <dd class="three-menu"><a href="javascript:void(0);" data-url="{{$v2->url}}">{{$v2->menu_name}}</a></dd>
                            @endforeach
                        </dl>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <iframe src="{{"/Admin/Index/Main"}}" frameborder="0" id="demoAdmin" style="width: 100%; height: 100%;"></iframe>
    </div>

    <div class="layui-card" id="prompt">
        <div class="layui-card-header" id="prompt--header">
            系统消息
            <a href="javascript:void(0);" id="cancel-bounced"><i class="layui-icon">&#x1006;</i></a>
        </div>
        <div class="layui-card-body prompt-text"></div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        技术支持：<a href="http://www.xxlzss.cn/" style="color:blue;" target="_blank">http://www.xxlzss.cn/</a>
    </div>
</div>
<script src="{{URL::asset("asset/layui/layui.js")}}"></script>
<script>
    //JavaScript代码区域
    layui.use(['element','layer'], function(){
        var element = layui.element,
            $ = layui.jquery,
            layer = layui.layer,
            editUrl = '{{"System/Administrator/Form/2"}}', //修改信息路径
            editAccountUrl = '{{"System/Administrator/FormAccount"}}', //重置账号路径
            tep = 0;

        //页面加载时运行
        $(document).ready(function(){
            var three_menu = $('.three-menu');
            var a = three_menu.children('a')[0];

            var demoAdmin = $('#demoAdmin');
            three_menu.eq(0).children('dd').removeClass('layui-this');
            three_menu.eq(0).addClass('layui-this');

            demoAdmin.prop('src', a.dataset.url);
        });

        //定义函数
        $.windowBox = {
            //点击二级菜单
            clickTwoMenu: function(obj){
                var that = obj;
                that.parent().parent().children('li').removeClass('layui-nav-itemed');
                that.parent().addClass('layui-nav-itemed');

                that.parent().children('dl').children('dd').removeClass('layui-this');
                that.parent().siblings().children('dl').children('dd').removeClass('layui-this');

                var demoAdmin = $('#demoAdmin');
                demoAdmin.prop('src', that.parent().children('dl').children('dd').eq(0).children('a').data('url'));

                that.parent().children('dl').children('dd').eq(0).addClass('layui-this');
            },
            //点击三级菜单
            clickThreeMenu: function(obj){
                var that = obj;
                var demoAdmin = $('#demoAdmin');

                that.parent().children('dd').removeClass('layui-this');
                that.addClass('layui-this');
                demoAdmin.prop('src',that.children('a').data('url'));
            },
            //没有评估的记录
            NoAssessRecord:function(){
                $.ajax({
                    url: '{:url("House.Assess/NoAssessRecord")}',
                    type: 'post',
                    dataType: 'json',
                    data:{

                    },
                    success:function(res){
                        var prompt = $('#prompt');
                        var prompt_text = $('.prompt-text');

                        var number = 0;
                        prompt.hide();
                        if(res.code == 1){
                            layer.msg(res.msg, {icon: 2});
                            return false;
                        }

                        if(res.data.no_assess_count > 0)
                        {
                            number++;
                            prompt.slideDown(1000);
                            var html = '<a href="'+ "{:url('House.Assess/index')}?view_assess=1" +'" target="_blank">'+number+'.您有'+res.data.no_assess_count+'条房源未评估'+'</a>';
                            prompt_text.eq(0).html(html);
                        }
//                    clearInterval(stop); //停止循环
                    },
                    error:function(error){
                        layer.msg(error, {icon: 2});
                    }
                });
            }
        };

        //点击二级菜单
        $('.two-menu-li').on('click',function(){
            $.windowBox.clickTwoMenu($(this));
        });
        //点击三级菜单
        $('.three-menu').on('click',function(){
            $.windowBox.clickThreeMenu($(this));
        });

        //选择一级菜单
        $('.one_menu').on('click',function (){
            var that = $(this);
            var id = $(this).data('id');
            var two_menu = $('.two-menu');

            two_menu.empty();
            $.ajax({
                url: '{:url("Index/GetTwoMenu")}',
                type: 'post',
                dataType: 'json',
                data:{
                    menu_id : id
                },
                success:function(res){
                    if(res.code == 1){
                        layer.msg(res.msg, {icon: 5});
                        return false;
                    }
                    var html = '';
                    var i = 1;
                    res.data.forEach(function(item){
                        html += '<li class="layui-nav-item '+ (i==1?'layui-nav-itemed':'') +'">';

                        html += '<a class="two-menu-li" href="javascript:;" data-id="'+ item.id +'">'+ item.menu_name +'</a>';
                        html += '<dl class="layui-nav-child">';

                        item.three_data.forEach(function (vo){
                            html += '<dd class="three-menu"><a href="javascript:void(0);" data-url="'+ vo.url +'" data-id="'+ vo.id +'">'+ vo.menu_name +'</a></dd>';

                            if(tep == 0)
                            {
                                var demoAdmin = $('#demoAdmin');

                                demoAdmin.prop('src', vo.url);
                            }
                            tep ++;
                        });

                        html += '</dl>';
                        html += '</li>';
                        i++ ;
                    });
                    two_menu.append(html);

                    //点击二级菜单
                    $('.two-menu-li').on('click',function(){
                        $.windowBox.clickTwoMenu($(this));
                    });

                    var three_menu = $('.three-menu');
                    three_menu.eq(0).children('dd').removeClass('layui-this');
                    three_menu.eq(0).addClass('layui-this');

                    //点击三级菜单
                    three_menu.on('click',function(){
                        $.windowBox.clickThreeMenu($(this));
                    });

                    tep = 0;
                },
                error:function(error){
                    layer.msg(error, {icon: 2});
                }
            })
        });

        //取消待处理提示框
        $('#cancel-bounced').on('click',function(){
            $('#prompt').css({'display':'none'});
        });

        //修改信息
        $('#edit-user-info').on('click', function() {
            layer.open({
                type: 2 //此处以iframe举例
                ,title: '修改'
                ,area: ['50%', '80%']
                ,shade: 0.3
                ,maxmin: true
                ,shadeClose: false //点击遮罩关闭层
                ,content: editUrl+'/'+{{$administrator_info['administrator_id']}}
                ,zIndex: layer.zIndex //重点1
                ,success: function(layero){
                    layer.setTop(layero); //重点2
                }
            });
        });

        //重置账号
        $('#edit-account').on('click', function() {
            layer.open({
                type: 2 //此处以iframe举例
                ,title: '重置账号'
                ,area: ['40%', '40%']
                ,shade: 0.3
                ,maxmin: true
                ,shadeClose: false //点击遮罩关闭层
                ,content: editAccountUrl+'/'+{{$administrator_info['administrator_id']}}
                ,zIndex: layer.zIndex //重点1
                ,success: function(layero){
                    layer.setTop(layero); //重点2
                }
            });
        });

        //退出操作
        $('#out').on('click',function(){
            $.ajax({
                url:'{{"/Admin/bWQ1X0xvZ2luL091dF9tZDU="}}',
                type:'post',
                dataType:'json',
                success:function(res){
                    if(res.code == 0){
                        location.href = '{{"/Admin/bWQ1X0xvZ2luL0luZGV4X21kNQ=="}}';
                    }
                },fail:function(error){
                    console.log(error);
                }
            });
        });

        //定时调用待处理接口
//        setInterval($.windowBox.NoAssessRecord(),0);
//        var stop = setInterval(function(){
//            $.windowBox.NoAssessRecord();
//        },60000);

    });

</script>
</body>
</html>
