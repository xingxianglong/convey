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
                <label class="layui-form-label"><span style="color:red;">*</span>小区名称：</label>
                <div class="layui-input-block">
                    <input type="text" name="community_name" value="{{isset($info->community_name)?$info->community_name:''}}" autocomplete="off" placeholder="请输入小区名称" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>建筑年代：</label>
                <div class="layui-input-block">
                    <input type="text" name="building_year" id="building_year" value="{{isset($info->building_year)?$info->building_year:''}}" autocomplete="off" placeholder="请选择建筑年代" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>建筑类型：</label>
                <div class="layui-input-block">
                    <select name="building_type_id">
                        <option value="">请选择建筑类型</option>
                        @foreach ($type_data as $k => $v)
                            @if (isset($info))
                                <option value="{{$v->id}}" {{$v->id == $info->building_type_id?'selected':''}}>{{$v->type_name}}</option>
                            @else
                                <option value="{{$v->id}}">{{$v->type_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>楼栋总数：</label>
                <div class="layui-input-block">
                    <input type="text" name="building_amount" value="{{isset($info->building_amount)?$info->building_amount:''}}" autocomplete="off" placeholder="请输入楼栋总数" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>房屋总数：</label>
                <div class="layui-input-block">
                    <input type="text" name="house_amount" value="{{isset($info->house_amount)?$info->house_amount:''}}" autocomplete="off" placeholder="请输入房屋总数" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>物业公司：</label>
                <div class="layui-input-block">
                    <input type="text" name="property_company" value="{{isset($info->property_company)?$info->property_company:''}}" autocomplete="off" placeholder="请输入物业公司" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>物业费：</label>
                <div class="layui-input-block">
                    <input type="text" name="property_fee" value="{{isset($info->property_fee)?$info->property_fee:''}}" autocomplete="off" placeholder="请输入物业费，元/m/月" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>开发商：</label>
                <div class="layui-input-block">
                    <input type="text" name="developers" value="{{isset($info->developers)?$info->developers:''}}" autocomplete="off" placeholder="请输入开发商" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color:red;">*</span>二手房价：</label>
                <div class="layui-input-block">
                    <input type="text" name="second_hand_price" value="{{isset($info->second_hand_price)?$info->second_hand_price:''}}" autocomplete="off" placeholder="请输入二手房价，单位元" class="layui-input">
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

    //建筑年代
    laydate.render({
        elem: '#building_year' //指定元素
        ,type: 'year'
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
            url = '{{"/Admin/House/Community/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/House/Community/Edit"}}';
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
                community_name:data.field.community_name,
                building_year:data.field.building_year,
                building_type_id:data.field.building_type_id,
                building_amount:data.field.building_amount,
                house_amount:data.field.house_amount,
                property_company:data.field.property_company,
                property_fee:data.field.property_fee,
                developers:data.field.developers,
                province_id:data.field.province_id,
                city_id:data.field.city_id,
                district_id:data.field.district_id,
                detail_address:data.field.detail_address,
                second_hand_price:data.field.second_hand_price,
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
