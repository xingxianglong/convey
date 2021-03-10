<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title></title>
    {load href="/static/layui/css/layui.css" /}
    <style>
        .layui-elem-quote{display: flex;justify-content: flex-end;}
    </style>
</head>

<body>
<div class="admin-main">
    <blockquote class="layui-elem-quote">
        <div class="layui-inline">
            <button class="layui-btn" id="reload" ><i class="layui-icon">&#xe666;</i>刷新</button>
        </div>
    </blockquote>
</div>
</body>

{load href="/static/layui/layui.js" /}
<script>
    layui.use(['table','laydate','form'] , function(){
        var table = layui.table
            , form = layui.form
            , laydate = layui.laydate
            , $ = layui.jquery
            , id = [];

        //刷新子页面(本页面)
        $('#reload').on('click', function() {
            location.reload();
        });

    });
</script>

</html>