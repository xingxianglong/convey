<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{URL::asset("asset/layui/css/layui.css")}}">
    <style>
        .layui-input-inline{max-width: 180px;}
        .select-input{margin: 0 0 5px 0;}
        .layui-form-label{width: 90px;}
        .laytable-cell-1-0-4 :hover{cursor: pointer;}
        #max-img{position: fixed;top:0;left:0;z-index: 1000;width: 100%;
            height: 100%;text-align: center;display: none;background:rgba(0,0,0,0.5);}
    </style>
</head>
<body>
<div class="admin-main">
    <blockquote class="layui-elem-quote">
        <div class="layui-inline">
            <button class="layui-btn" id="add"><i class="layui-icon">&#xe61f;</i>新增</button>
        </div>
        <div class="layui-inline">
            <button class="layui-btn layui-btn-danger" id="batch-delete"><i class="layui-icon">&#xe640;
            </i>批量删除</button>
        </div>
        <div class="layui-inline">
            <button class="layui-btn" id="reload" ><i class="layui-icon">&#xe666;</i>刷新</button>
        </div>
    </blockquote>
    <div class="layui-row layui-form">
        <div class="layui-col-xs11">
            <div class="grid-demo grid-demo-bg1">
                <div class="layui-inline select-input">
                    <label class="layui-form-label">更新时间</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="dateSelect" placeholder="日期范围">
                    </div>
                </div>

                <div class="layui-inline select-input">
                    <label class="layui-form-label">ID</label>
                    <div class="layui-input-inline">
                        <input type="text" name="id" autocomplete="off" placeholder="请输入ID" class="layui-input">
                    </div>
                </div>

                <div class="layui-inline select-input">
                    <label class="layui-form-label">配置名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="configuration_name" autocomplete="off" placeholder="请输入配置名称" class="layui-input">
                    </div>
                </div>
            </div>
        </div>
        <!---->
        <div class="layui-col-xs1" align="right">
            <div class="layui-inline">
                <button class="layui-btn" lay-submit lay-filter="search"><i class="layui-icon">&#xe615;
                </i>搜索</button>
            </div>
        </div>
    </div>
    <table class="layui-hide" id="demo"  lay-filter="teacherTab"></table>

    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" data-id="@{{d.id}}" lay-event="edit">修改</a>

        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="delete">删除</a>
    </script>

</div>

<!-- 放大的图片 begin -->
<div id="max-img" style="" title="点击关闭">
    <img src="" alt="" style="height: 100%;" />
</div>
<!-- 放大的图片 end -->
</body>
<script src="{{URL::asset("asset/layui/layui.js")}}"></script>
<script>
layui.use(['table','laydate','form'] , function(){
    var table = layui.table
            , form = layui.form
            , laydate = layui.laydate
            , $ = layui.jquery
            , dateSelect = ''
            , getPageUrl = '{{"GetPage"}}' //分页数据路径
            , addUrl = '{{"Form/1/0"}}' //新增路径
            , editUrl = '{{"Form/2"}}' //修改路径
            , deleteUrl = '{{"Delete"}}' //删除路径
            , id = [];


    //表格数据
    table.render({
        elem: '#demo'
        ,height: 'full-140'
        ,url: getPageUrl
        ,method: 'post'
        ,where:{

        }
        ,cols: [[ //标题栏
            {type:'checkbox'}
            ,{field: 'id', title: 'ID',width:100}
            ,{field: 'configuration_name', title: '配置名称',minWidth:100}
            ,{field: '', title: '图标',width:130,templet:function(d){
                var str = '<img src="'+ d.icon +'" style="" />';
                return str;
            }}
            ,{field: '', title: '是否上架',width:90,templet:function(d){
                    var str = '否';
                    if(d.is_show == 1)
                    {
                        str = '是';
                    }
                    return str;
                }}
            ,{field: '', title: '是否置顶',width:90,templet:function(d){
                    var str = '否';
                    if(d.is_top == 1)
                    {
                        str = '是';
                    }
                    return str;
                }}
            ,{field: 'sort', title: '排序号',width:120}
            ,{field: 'update_administrator_name', title: '更新人',minWidth:100}
            ,{field: 'update_time', title: '更新时间',width:170}
            ,{fixed: 'right', align:'center', toolbar: '#barDemo', title: '操作',width:120}
        ]]
        //,data: data
        // ,skin: 'line' //表格风格
        ,even: false
        ,page: true //是否显示分页
        ,limits: [10, 20, 30]
        ,limit: 10 //每页默认显示的数量
        ,done:function(res, curr, count) {
            $('.laytable-cell-1-0-3').click('on', function () {
                var that = $(this);
                var max_business_license = $('#max-img');

                max_business_license.fadeIn(500);
                max_business_license.children('img').attr('src',that.children('img').attr('src'));
            });
            $('#max-img').click('on', function () {
                var that = $(this);
                that.fadeOut(500);
            });
        }
    });


    //监听工具条
    table.on('tool(teacherTab)', function(obj){
        var data = obj.data;
        if(obj.event === 'detail'){

        } else if(obj.event === 'delete'){
            layer.confirm('确认删除吗', function(index){
                $.ajax({
                    url: deleteUrl,
                    type:'post',
                    dataType:'json',
                    async: false,
                    data:{
                        id:data.id
                    },
                    success:function(res){
                        if(res.code == 1){
                            layer.msg(res.msg,{icon:2,time:2000});
                            return false;
                        }
                        layer.msg(res.msg,{icon:1,time:2000});
                        table.reload('demo', {
                            url: getPageUrl
                            ,method: 'post'
                            ,where: {

                            }
                            ,page: {
                                curr: 1 //重新从第 1 页开始
                            }
                        });
                    },
                    fail:function(error){
                        layui.msg(error,{icon:2});
                    }
                });
            });
        } else if(obj.event === 'edit'){
            active['edit'].call(this);
        }
    });
    // 点击事件的弹出层
    var active = {
        edit:function () {
            var that = $(this);
            var id = that.data('id');

            //多窗口模式，层叠置顶
            layer.open({
                type: 2 //此处以iframe举例
                ,title: '修改'
                ,area: ['75%', '70%']
                ,shade: 0.3
                ,maxmin: true
                ,shadeClose: false //点击遮罩关闭层
                ,content: editUrl+'/'+id
                ,zIndex: layer.zIndex //重点1
                ,success: function(layero){
                    layer.setTop(layero); //重点2
                }
            });
        }
    };

    //日期选择器
    laydate.render({
        elem: '#dateSelect'
        ,range: '~'
        ,min: '2000-01-01'
        ,max: '2050-12-31'
        ,format: 'yyyy-MM-dd'
        ,done: function(value, date, endDate){
            dateSelect = value;
        }
    });

    table.on('checkbox(teacherTab)', function(obj){
        id = [] ;
        //获取已选
        var checkStatus = table.checkStatus('demo')
                ,data = checkStatus.data;
        //遍历
        for(let i = 0 ; i < data.length ; i++){
            id.push(data[i].id)
        }
    });

    //批量删除
    $('#batch-delete').on('click',function(){
        if(id.length != '0'){
            //询问框
            layer.confirm('确认删除吗', {
                btn: ['确认','取消'] //按钮
            }, function(){
                $.ajax({
                    url: deleteUrl,
                    type:'post',
                    dataType:'json',
                    data:{
                        id:id
                    },success:function(res){
                        if(res.code == 1){
                            layer.msg(res.msg,{icon:2,time:2000});
                            return false;
                        }
                        layer.msg(res.msg,{icon:1,time:2000});
                        table.reload('demo', {
                            url: getPageUrl
                            ,method: 'post'
                            ,where: {

                            }
                            ,page: {
                                curr: 1 //重新从第 1 页开始
                            }
                        });

                    },fail:function(error){
                        layer.msg(error,{icon:2,time:2000});
                    }
                })
            });
        }
    });

    //搜索按钮事件
    form.on('submit(search)',function(data){
        data.field.dateSelect = dateSelect;
        table.reload('demo', {
            url: getPageUrl,
            type:'post',
            where: { //设定异步数据接口的额外参数，任意设
                param: data.field
            }
            ,page: {
                curr: 1 //重新从第 1 页开始
            }
        });
    });

    //添加按钮事件
    $('#add').on('click', function() {
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '新增'
            ,area: ['75%', '70%']
            ,shade: 0.3
            ,maxmin: true
            ,shadeClose: false //点击遮罩关闭层
            ,content: addUrl
            ,zIndex: layer.zIndex //重点1
            ,success: function(layero){
                layer.setTop(layero); //重点2
            }
        });
    });

    //刷新子页面(本页面)
    $('#reload').on('click', function() {
        location.reload();
    });

});
</script>
</html>
