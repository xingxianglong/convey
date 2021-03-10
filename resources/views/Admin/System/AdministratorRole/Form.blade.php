<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{URL::asset('asset/layui/css/layui.css')}}">
    <style>
        #submit .layui-input-block{margin:0;}
        #submit{position: fixed;bottom: 0;background-color: #dedede;margin: 0;width: 100%;text-align:center;}
        .hide{display: none}
        .one-menu{border-top:1px solid #dedede;border-bottom:1px solid #dedede;margin-bottom: 10px;}
        .two-menu{margin: 0 0 0 25px;}
        .three-menu{margin: 0 0 0 25px;}
    </style>
</head>
<body>
<form class="layui-form" style="padding: 10px 10px 50px 0;">
    <div class="layui-form-item">
        <label class="layui-form-label"><span style="color:red;">*</span>角色名称：</label>
        <div class="layui-input-inline">
            <input type="text" name="role_name" id="role_name" value="{{isset($info->role_name)?$info->role_name:''}}" autocomplete="off" placeholder="请输入角色名称" class="layui-input">
        </div>
    </div>

    <div class="layui-card">
        <div class="layui-card-header" style="margin-left:10px;">权限设置：</div>
        <div class="layui-card-body">
            @foreach ($menu_data as $k => $v)
                <div class="one-menu layui-form" lay-filter="one-menu">
{{--                    <input type="checkbox" name="one_menu_id[]" lay-filter="one_menu_id" lay-skin="primary" value="{$vo.id}" title="{$vo.menu_name}">--}}
                    <div style="color:#666;">
                        {{$v->menu_name}}
                    </div>
                    <div class="two-menu layui-form" lay-filter="two-menu">
                        @foreach ($v->two_data as $k2 => $v2)
                            @if (isset($info) && in_array($v2->menu_id,$info->menu_id))
                                <input type="checkbox" checked class="two-menu-id" name="two_menu_id[]" lay-filter="two_menu_id" lay-skin="primary" value="{{$v2->id}}" title="{{$v2->menu_name}}">
                            @else
                                <input type="checkbox" class="two-menu-id" name="two_menu_id[]" lay-filter="two_menu_id" lay-skin="primary" value="{{$v2->id}}" title="{{$v2->menu_name}}">
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="layui-form-item" id="submit">
        <div class="layui-input-block">
            <button id="btn" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
            <button id="button-cancel" type="button" class="layui-btn layui-btn-normal">取消</button>
        </div>
    </div>
</form>

<script src="{{URL::asset('asset/layui/layui.js')}}"></script>
<script>
layui.use(['form', 'layedit', 'laydate','upload','element','table'], function(){
    var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery,
            isSubmit = 0, //是否已提交
            id = '{{isset($id) ? $id : ""}}',
            jump_type = '{{$jump_type}}';

    //一级菜单
    form.on('checkbox(one_menu_id)', function(data){
        var that = data.othis; //得到美化后的DOM对象
        var isChecked = data.elem.checked; //是否被选中，true或者false
        var value = data.value; //复选框value值，也可以通过data.elem.value得到

        if(isChecked){
            that.siblings().find('input').prop('checked',true);
        }else{
            that.siblings().find('input').prop('checked',false);
        }

        form.render('checkbox','two-menu'); //渲染
    });

    //二级菜单
    form.on('checkbox(two_menu_id)', function(data){
        var that = data.othis; //得到美化后的DOM对象
        var isChecked = data.elem.checked; //是否被选中，true或者false
        var value = data.value; //复选框value值，也可以通过data.elem.value得到

        if(isChecked){
            that.next().find('input').prop('checked',true);
        }else{
            that.next().find('input').prop('checked',false);
        }

    });


    //jQuery 自动加载处理
    $(document).ready(function(){
        //二级全选，一级为选中
        var two_menu = $('.two-menu');

        for(var i=0;i<two_menu.length;i++)
        {
            var tep = 0;
            for(var j=0;j<two_menu.eq(i).children('input').length;j++)
            {
                if(two_menu.eq(i).children('input').eq(j).is(':checked') == true)
                {
                    tep ++;
                }
            }
            if(tep == two_menu.eq(i).children('input').length)
            {
                two_menu.eq(i).parent().children('input').prop('checked',true);
                form.render('checkbox','two-menu'); //渲染
            }
        }

        //二级全选，一级为选中
        var two_menu = $('.two-menu');

        for(var i=0;i<two_menu.length;i++)
        {
            var tep = 0;
            for(var j=0;j<two_menu.eq(i).children('input').length;j++)
            {
                if(two_menu.eq(i).children('input').eq(j).is(':checked') == true)
                {
                    tep ++;
                }
            }
            if(tep == two_menu.eq(i).children('input').length)
            {
                two_menu.eq(i).parent().children('input').prop('checked',true);
                form.render('checkbox','one-menu'); //渲染
            }
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
            url = '{{"/Admin/System/AdministratorRole/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/System/AdministratorRole/Edit"}}';
        }
        else
        {
            layer.msg('跳转参数错误',{icon:2,time:2000});
        }
        var two_menu_id_arr = [];
        var two_menu_id = $('.two-menu-id');

        for(var i=0;i<two_menu_id.length;i++){
            if(two_menu_id.eq(i).prop('checked')){
                two_menu_id_arr.push(two_menu_id.eq(i).val());
            }
        }

        var function_permissions_id_arr = [];
        var function_permissions_id = $('.function-permissions-id');

        for(var i=0;i<function_permissions_id.length;i++){
            if(function_permissions_id.eq(i).prop('checked')){
                function_permissions_id_arr.push(function_permissions_id.eq(i).val());
            }
        }

        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data:{
                role_name:data.field.role_name,
                two_menu_id:two_menu_id_arr,
                function_permissions_id:function_permissions_id_arr,
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
