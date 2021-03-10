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
                    <label class="layui-form-label">门店名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="store_name" autocomplete="off" placeholder="请输入门店名称" class="layui-input">
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
            , getPageUrl = '{{"/Admin/Store/Store/GetPage"}}' //分页数据路径
            , isRadio = '{{$is_radio==1 ? "radio" : "checkbox"}}' //是否单选
            , id = [];


    //表格数据
    table.render({
        elem: '#demo'
        ,height: 'full-70'
        ,url: getPageUrl
        ,method: 'post'
        ,where:{

        }
        ,cols: [[ //标题栏
            {type:isRadio}
            ,{field: 'id', title: 'ID',width:100}
            ,{field: 'store_name', title: '门店名称',minWidth:100}
            ,{field: '', title: '封面图',width:130,templet:function(d){
                var str = '<img src="'+ d.cover +'" style="" />';
                return str;
            }}
            ,{field: 'note', title: '备注',width:120}
            ,{field: '', title: '地址',width:300,templet:function(d){
                var str = d.province_name + '-' + d.city_name + '-' + d.district_name + '-' + d.detail_address;
                return str;
            }}
            ,{field: 'update_administrator_name', title: '更新人',minWidth:100}
            ,{field: 'update_time', title: '更新时间',width:170}
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

    //刷新子页面(本页面)
    $('#reload').on('click', function() {
        location.reload();
    });

});
</script>
</html>
