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
        .div-flex{display: flex;flex-wrap:wrap;align-items: center;border: 1px solid #dedede;}
        .banner-img{flex:0 0 18%;text-align: center;margin: 10px 10px;height: 200px;line-height: 200px;position: relative;}
        .banner-img img{width: 100%;height: 100%;}
        .banner-img .delete-img{position: absolute;top:0;right:0;}
        #upload-banner{font-size: 100px;cursor: pointer;}
        .configuration-div{display: flex;flex-wrap:wrap;width: 100%;}
        .configuration-pro{width: 20%;text-align: center;margin-bottom: 20px;}
    </style>
</head>
<body>
<form class="layui-form" style="padding: 10px 10px 50px 0;">
    <div class="layui-tab">
        <ul class="layui-tab-title">
            <li class="layui-this">基础信息</li>
            <li>轮播图</li>
            <li>标签</li>
            <li>配置</li>
        </ul>
        <div class="layui-tab-content">
            {{--    基础信息begin        --}}
            <div class="layui-tab-item layui-show">
                <div class="layui-row layui-col-space10">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red">*</span>门店：</label>
                            <div class="layui-input-block">
                                <button class="layui-btn" id="select-store">选择门店</button>
                                <input type="hidden" name="store_id" id="store-id" value="{{isset($info->store_id) ? $info->store_id : ''}}" />
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">门店信息：</label>
                            <div class="layui-input-block">
                                <input type="text" name="store_info" id="store_info" value="{{isset($info->store_name) ? $info->store_name : ''}}{{isset($info->note) ? '('.$info->note.')' : ''}}" autocomplete="off" placeholder="请选择门店" class="layui-input" disabled>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red">*</span>顾问：</label>
                            <div class="layui-input-block">
                                <button class="layui-btn" id="select-consultant">选择顾问</button>
                                <input type="hidden" name="consultant_id" id="consultant-id" value="{{isset($info->consultant_id) ? $info->consultant_id : ''}}" />
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">顾问信息：</label>
                            <div class="layui-input-block">
                                <input type="text" name="consultant_info" id="consultant_info" value="{{isset($info->consultant_name) ? $info->consultant_name : ''}}{{isset($info->consultant_phone) ? $info->consultant_phone : ''}}" autocomplete="off" placeholder="请选择顾问" class="layui-input" disabled>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red">*</span>小区：</label>
                            <div class="layui-input-block">
                                <button class="layui-btn" id="select-community">选择小区</button>
                                <input type="hidden" name="community_id" id="community-id" value="{{isset($info->community_id) ? $info->community_id : ''}}" />
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">小区信息：</label>
                            <div class="layui-input-block">
                                <input type="text" name="community_info" id="community_info" value="{{isset($info->community_name) ? $info->community_name : ''}}" autocomplete="off" placeholder="请选择小区" class="layui-input" disabled>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>标题：</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{{isset($info->title)?$info->title:''}}" autocomplete="off" placeholder="请输入标题" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>租金：</label>
                            <div class="layui-input-block">
                                <input type="text" name="rent" value="{{isset($info->rent)?$info->rent:''}}" autocomplete="off" placeholder="请输入租金，单位元" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>面积：</label>
                            <div class="layui-input-block">
                                <input type="text" name="acreage" value="{{isset($info->acreage)?$info->acreage:''}}" autocomplete="off" placeholder="请输入面积" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">品牌公寓：</label>
                            <div class="layui-input-block">
                                <select name="brand_apartment_id">
                                    <option value="">请选择品牌公寓</option>
                                    @foreach ($brand_apartment_data as $k => $v)
                                        @if (isset($info))
                                            <option value="{{$v->id}}" {{$v->id == $info->brand_apartment_id?'selected':''}}>{{$v->apartment_name}}</option>
                                        @else
                                            <option value="{{$v->id}}">{{$v->apartment_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>分类：</label>
                            <div class="layui-input-block">
                                <select name="classify_id">
                                    <option value="">请选择分类</option>
                                    @foreach ($classify_data as $k => $v)
                                        @if (isset($info))
                                            <option value="{{$v->id}}" {{$v->id == $info->classify_id?'selected':''}}>{{$v->classify_name}}</option>
                                        @else
                                            <option value="{{$v->id}}">{{$v->classify_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>朝向：</label>
                            <div class="layui-input-block">
                                <select name="direction_id">
                                    <option value="">请选择朝向</option>
                                    @foreach ($direction_data as $k => $v)
                                        @if (isset($info))
                                            <option value="{{$v->id}}" {{$v->id == $info->direction_id?'selected':''}}>{{$v->direction_name}}</option>
                                        @else
                                            <option value="{{$v->id}}">{{$v->direction_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>装修：</label>
                            <div class="layui-input-block">
                                <select name="decorate_id">
                                    <option value="">请选择装修</option>
                                    @foreach ($decorate_data as $k => $v)
                                        @if (isset($info))
                                            <option value="{{$v->id}}" {{$v->id == $info->decorate_id?'selected':''}}>{{$v->decorate_name}}</option>
                                        @else
                                            <option value="{{$v->id}}">{{$v->decorate_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>付款方式：</label>
                            <div class="layui-input-block">
                                <select name="payment_way_id">
                                    <option value="">请选择付款方式</option>
                                    @foreach ($payment_way_data as $k => $v)
                                        @if (isset($info))
                                            <option value="{{$v->id}}" {{$v->id == $info->payment_way_id?'selected':''}}>{{$v->way_name}}</option>
                                        @else
                                            <option value="{{$v->id}}">{{$v->way_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
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
                            <label class="layui-form-label"><span style="color:red;">*</span>室：</label>
                            <div class="layui-input-block">
                                <select name="room">
                                    <option value="">请选择室</option>
                                    @for ($i = 0; $i < 100; $i++)
                                        <option value="{{$i}}" @if (isset($info) && $i == $info->room) selected @endif>{{$i}}室</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>厅：</label>
                            <div class="layui-input-block">
                                <select name="hall">
                                    <option value="">请选择厅</option>
                                    @for ($i = 0; $i < 100; $i++)
                                        <option value="{{$i}}" @if (isset($info) && $i == $info->hall) selected @endif>{{$i}}厅</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>卫：</label>
                            <div class="layui-input-block">
                                <select name="toilet">
                                    <option value="">请选择卫</option>
                                    @for ($i = 0; $i < 100; $i++)
                                        <option value="{{$i}}" @if (isset($info) && $i == $info->toilet) selected @endif>{{$i}}卫</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>阳台：</label>
                            <div class="layui-input-block">
                                <select name="balcony">
                                    <option value="">请选择阳台</option>
                                    @for ($i = 0; $i < 100; $i++)
                                        <option value="{{$i}}" @if (isset($info) && $i == $info->balcony) selected @endif>{{$i}}阳台</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>厨：</label>
                            <div class="layui-input-block">
                                <select name="kitchen">
                                    <option value="">请选择厨</option>
                                    @for ($i = 0; $i < 100; $i++)
                                        <option value="{{$i}}" @if (isset($info) && $i == $info->kitchen) selected @endif>{{$i}}厨</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>总楼层：</label>
                            <div class="layui-input-block">
                                <select name="total_floor">
                                    <option value="">请选择总楼层</option>
                                    @for ($i = 0; $i < 100; $i++)
                                        <option value="{{$i}}" @if (isset($info) && $i == $info->total_floor) selected @endif>{{$i}}层</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>楼层：</label>
                            <div class="layui-input-block">
                                <select name="floor">
                                    <option value="">请选择楼层</option>
                                    @for ($i = 0; $i < 100; $i++)
                                        <option value="{{$i}}" @if (isset($info) && $i == $info->floor) selected @endif>{{$i}}层</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        @if ($is_deal == 1)
                            <div class="layui-form-item">
                                <label class="layui-form-label"><span style="color:red;">*</span>成交日期：</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" value="{{isset($info->trading_time)?$info->trading_time:''}}" placeholder="请选择成交日期" autocomplete="off" name="trading_time" id="trading_time">
                                </div>
                            </div>
                        @endif

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>整租合租：</label>
                            <div class="layui-input-block">
                                @if (isset($info))
                                    <input type="radio" name="entire_or_joint" value="1" title="整租" {{$info->entire_or_joint==1?'checked':''}}>
                                    <input type="radio" name="entire_or_joint" value="2" title="合租" {{$info->entire_or_joint==2?'checked':''}}>
                                @else
                                    <input type="radio" name="entire_or_joint" value="1" title="整租" checked>
                                    <input type="radio" name="entire_or_joint" value="2" title="合租" >
                                @endif
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>优选好房：</label>
                            <div class="layui-input-block">
                                @if (isset($info))
                                    <input type="radio" name="is_optimization" value="1" title="是" {{$info->is_optimization==1?'checked':''}}>
                                    <input type="radio" name="is_optimization" value="2" title="否" {{$info->is_optimization==2?'checked':''}}>
                                @else
                                    <input type="radio" name="is_optimization" value="1" title="是">
                                    <input type="radio" name="is_optimization" value="2" title="否" checked>
                                @endif
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>钥匙：</label>
                            <div class="layui-input-block">
                                @if (isset($info))
                                    <input type="radio" name="is_key" value="1" title="有" {{$info->is_key==1?'checked':''}}>
                                    <input type="radio" name="is_key" value="2" title="没有" {{$info->is_key==2?'checked':''}}>
                                @else
                                    <input type="radio" name="is_key" value="1" title="有" checked>
                                    <input type="radio" name="is_key" value="2" title="没有" >
                                @endif
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><span style="color:red;">*</span>电梯：</label>
                            <div class="layui-input-block">
                                @if (isset($info))
                                    <input type="radio" name="is_elevator" value="1" title="有" {{$info->is_elevator==1?'checked':''}}>
                                    <input type="radio" name="is_elevator" value="2" title="没有" {{$info->is_elevator==2?'checked':''}}>
                                @else
                                    <input type="radio" name="is_elevator" value="1" title="有" checked>
                                    <input type="radio" name="is_elevator" value="2" title="没有" >
                                @endif
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
            </div>

            {{--    基础信息end        --}}

            {{--    轮播图begin        --}}
            <div class="layui-tab-item">
                <div id="div-banner-img" class="div-flex">
                    <div class="banner-img">
                        <i class="layui-icon" id="upload-banner" title="上传图片">&#xe654;</i>
                    </div>

                    @if (isset($info))
                        @foreach ($info->banner_data as $k => $v)
                            <div class="banner-img">
                                <img src="{{$v->img}}" alt=""/>
                                <input type="hidden" name="img[]" class="banner-img-value" value="{{$v->img}}" />
                                <input type="hidden" name="img_ext[]" class="banner-img-ext-value" value="{{$v->img_ext}}" />
                                <input type="hidden" name="img_size[]" class="banner-img-size-value" value="{{$v->img_size}}" />
                                <button class="layui-btn layui-btn-danger layui-btn-sm delete-img" title="删除">
                                    <i class="layui-icon">&#xe640;</i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            {{--    轮播图end        --}}

            {{--    标签begin        --}}
            <div class="layui-tab-item">
                <div class="layui-row layui-col-space10">
                    <div class="layui-col-md12">
                        <div style="display: flex;flex-wrap:wrap;width: 100%;">
                            @foreach ($label_data as $k => $v)
                                @if (isset($info))
                                    <div style="flex:0 20%;margin-top: 10px;margin-bottom: 10px;">
                                        <input type="checkbox" name="label_id[]" class="label_id" value="{{$v->id ? $v->id : ''}}" title="{{$v->label_name ? $v->label_name : ''}}" @if (in_array($v->id,$info->label_data)) checked @endif>
                                    </div>
                                @else
                                    <div style="flex:0 20%;margin-top: 10px;margin-bottom: 10px;">
                                        <input type="checkbox" name="label_id[]" class="label_id" value="{{$v->id ? $v->id : ''}}" title="{{$v->label_name ? $v->label_name : ''}}" >
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            {{--    标签end        --}}

            {{--    配置bugin        --}}
            <div class="layui-tab-item">
                <div class="layui-row layui-col-space10">
                    <div class="layui-col-md12">
                        <div class="configuration-div">
                            @foreach ($configuration_data as $k => $v)
                                <div class="configuration-pro">
                                    <div>
                                        <img style="width:90px;height: 90px;" src="{{$v->icon ? $v->icon : ''}}"/>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="configuration_id[]" class="configuration_id" value="{{$v->id ? $v->id : ''}}" title="{{$v->configuration_name ? $v->configuration_name : ''}}" @if (isset($info) && in_array($v->id,$info->configuration_data)) checked @endif>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            {{--    配置end        --}}
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

    //选择门店
    $('#select-store').on('click',function(){
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '选择门店'
            ,area: ['90%', '90%']
            ,shade: 0.3
            ,maxmin: true
            ,shadeClose: false //点击遮罩关闭层
            ,closeBtn:1
            ,content: '{{"/Admin/Store/Store/Select/1"}}'
            ,zIndex: layer.zIndex //重点1
            ,btn:['选择','取消']
            ,yes: function(index, layero){
                var body = layer.getChildFrame('body',index);

                var iframeWin = window[layero.find('iframe')[0]['name']];

                var table_checkbox = iframeWin.layui.table.checkStatus('demo');

                if(table_checkbox['data'].length <= 0){
                    layer.msg('请选择门店',{icon:2,time:2000});
                }

                if(isNaN(parseInt(table_checkbox['data'][0]['id']))){
                    layer.msg('找不到门店id',{icon:2,time:2000});
                }
                var store_id = table_checkbox['data'][0]['id'];

                $.ajax({
                    url:'{{"/Admin/Store/Store/GetInfo/"}}'+store_id,
                    type:'get',
                    dataType:'json',
                    data:{

                    },success:function(res){
                        if(res.code == 0 ){
                            var consultant_id = $('input[name="consultant_id"]');
                            var consultant_info = $('input[name="consultant_info"]');

                            consultant_id.val('');
                            consultant_info.val('');

                            var store_id = $('input[name="store_id"]');
                            var store_info = $('input[name="store_info"]');

                            store_id.val(res.data.id);

                            var store_info_str = '';
                            store_info_str += res.data.store_name;
                            if(res.data.note != '')
                            {
                                store_info_str += '(' + res.data.note + ')';
                            }
                            store_info.val(store_info_str);

                            layer.close(layer.index);
                        }else{
                            layer.msg(res.msg,{icon:2,time:2000});
                        }
                    },fail:function(error){
                        console.log(error);
                    }
                });
            }
        });
        return false;
    });

    //选择顾问
    $('#select-consultant').on('click',function(){
        var store_id = $('input[name="store_id"]');

        if(store_id.val() == '')
        {
            layer.msg('请先选择门店',{icon:2,time:2000});
            return false;
        }

        layer.open({
            type: 2 //此处以iframe举例
            ,title: '选择顾问'
            ,area: ['90%', '90%']
            ,shade: 0.3
            ,maxmin: true
            ,shadeClose: false //点击遮罩关闭层
            ,closeBtn:1
            ,content: '{{"/Admin/Store/Consultant/Select/1/"}}'+store_id.val()
            ,zIndex: layer.zIndex //重点1
            ,btn:['选择','取消']
            ,yes: function(index, layero){
                var body = layer.getChildFrame('body',index);

                var iframeWin = window[layero.find('iframe')[0]['name']];

                var table_checkbox = iframeWin.layui.table.checkStatus('demo');

                if(table_checkbox['data'].length <= 0){
                    layer.msg('请选择顾问',{icon:2,time:2000});
                }

                if(isNaN(parseInt(table_checkbox['data'][0]['id']))){
                    layer.msg('找不到顾问id',{icon:2,time:2000});
                }
                var consultant_id = table_checkbox['data'][0]['id'];

                $.ajax({
                    url:'{{"/Admin/Store/Consultant/GetInfo/"}}'+consultant_id,
                    type:'get',
                    dataType:'json',
                    data:{

                    },success:function(res){
                        if(res.code == 0 ){
                            var consultant_id = $('input[name="consultant_id"]');
                            var consultant_info = $('input[name="consultant_info"]');

                            consultant_id.val(res.data.id);

                            var consultant_info_str = '';
                            consultant_info_str += res.data.consultant_name;
                            consultant_info_str += '(' + res.data.phone + ')';
                            consultant_info.val(consultant_info_str);

                            layer.close(layer.index);
                        }else{
                            layer.msg(res.msg,{icon:2,time:2000});
                        }
                    },fail:function(error){
                        console.log(error);
                    }
                });
            }
        });
        return false;
    });

    //选择小区
    $('#select-community').on('click',function(){
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '选择小区'
            ,area: ['90%', '90%']
            ,shade: 0.3
            ,maxmin: true
            ,shadeClose: false //点击遮罩关闭层
            ,closeBtn:1
            ,content: '{{"/Admin/House/Community/Select/1"}}'
            ,zIndex: layer.zIndex //重点1
            ,btn:['选择','取消']
            ,yes: function(index, layero){
                var body = layer.getChildFrame('body',index);

                var iframeWin = window[layero.find('iframe')[0]['name']];

                var table_checkbox = iframeWin.layui.table.checkStatus('demo');

                if(table_checkbox['data'].length <= 0){
                    layer.msg('请选择小区',{icon:2,time:2000});
                }

                if(isNaN(parseInt(table_checkbox['data'][0]['id']))){
                    layer.msg('找不到小区id',{icon:2,time:2000});
                }
                var community_id = table_checkbox['data'][0]['id'];

                $.ajax({
                    url:'{{"/Admin/House/Community/GetInfo/"}}'+community_id,
                    type:'get',
                    dataType:'json',
                    data:{

                    },success:function(res){
                        if(res.code == 0 ){
                            var community_id = $('input[name="community_id"]');
                            var community_info = $('input[name="community_info"]');

                            community_id.val(res.data.id);

                            var community_info_str = '';
                            community_info_str += res.data.community_name;
                            community_info.val(community_info_str);

                            layer.close(layer.index);
                        }else{
                            layer.msg(res.msg,{icon:2,time:2000});
                        }
                    },fail:function(error){
                        console.log(error);
                    }
                });
            }
        });
        return false;
    });

    //上传图片
    var uploadImg = upload.render({
        elem: '#cover-img' //绑定元素
        ,url: '{{"/Admin/bWQ1X1VwbG9hZC9JbWdfbWQ1"}}' //上传接口
        ,method : 'post'
        ,accept : 'file'
        ,exts : 'png|jpg|jpeg|gif'
        ,data: {
            file_path : 'house'
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

    //成交日期
    laydate.render({
        elem: '#trading_time' //指定元素
    });

    //上传banner
    var uploadImg2 = upload.render({
        elem: '#upload-banner' //绑定元素
        ,url: '{{"/Admin/bWQ1X1VwbG9hZC9JbWdfbWQ1"}}' //上传接口
        ,method : 'post'
        ,accept : 'file'
        ,multiple: true
        ,exts : 'png|jpg|jpeg|gif'
        ,data: {
            file_path : 'house_banner'
        }
        ,done: function(res){
            if(res.code == 1){
                layer.msg(res.msg,{icon:2,time:2000});
                return false;
            }
            //上传完毕回调
            var banner_img = $('#div-banner-img');
            var html = '';

            html += '<div class="banner-img">';
            html += '<img src="'+ res.data.src +'" alt=""/>';
            html += '<input type="hidden" name="img[]" class="banner-img-value" value="'+ res.data.src +'" />';
            html += '<input type="hidden" name="img_ext[]" class="banner-img-ext-value" value="'+ res.data.ext +'" />';
            html += '<input type="hidden" name="img_size[]" class="banner-img-size-value" value="'+ res.data.size +'" />';
            html += '<button class="layui-btn layui-btn-danger layui-btn-sm delete-img" title="删除">';
            html += '<i class="layui-icon">&#xe640;</i>';
            html += '</button>';

            html += '</div>';
            banner_img.append(html);

            $('.delete-img').on('click',function(){
                var that = $(this);
                $.windowBox.delete_img(that);
            });

            layer.msg(res.msg,{icon:1,time:2000});
        }
        ,error: function(error){
            //请求异常回调
            layer.msg(error,{icon:2,time:2000});
        }
    });

    //删除banner图
    $('.delete-img').on('click',function(){
        var that = $(this);
        $.windowBox.delete_img(that);
    });

    $.windowBox = {
        //删除banner图
        delete_img:function(obj){
            obj.parent().remove();
            return false;
        }
    };

    //监听提交
    form.on('submit(demo1)', function(data){
        if(isSubmit == 1){
            layer.msg('你已提交，请勿重复点击',{icon:5,time:2000});
            return false;
        }

        var url = '';
        if(jump_type == 1)
        {
            url = '{{"/Admin/House/House/Add"}}';
        }
        else if(jump_type == 2)
        {
            url = '{{"/Admin/House/House/Edit"}}';
        }
        else
        {
            layer.msg('跳转参数错误',{icon:2,time:2000});
        }


        //轮播图
        var img_arr = [];
        var img_ext_arr = [];
        var img_size_arr = [];

        for(var i=0;i<$('.banner-img-value').length;i++){
            img_arr.push($('.banner-img-value').eq(i).val());
            img_ext_arr.push($('.banner-img-ext-value').eq(i).val());
            img_size_arr.push($('.banner-img-size-value').eq(i).val());
        }

        //标签
        var label_id_arr = [];
        for(var i=0;i<$('.label_id').length;i++){
            if($('.label_id').eq(i).prop('checked') == true)
            {
                label_id_arr.push($('.label_id').eq(i).val());
            }
        }

        //设备数量
        var configuration_id_arr = [];

        for(var i=0;i<$('.configuration_id').length;i++){
            if($('.configuration_id').eq(i).prop('checked') == true)
            {
                configuration_id_arr.push($('.configuration_id').eq(i).val());
            }
        }

        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data:{
                id:id,
                store_id:data.field.store_id,
                consultant_id:data.field.consultant_id,
                community_id:data.field.community_id,
                brand_apartment_id:data.field.brand_apartment_id,
                classify_id:data.field.classify_id,
                direction_id:data.field.direction_id,
                decorate_id:data.field.decorate_id,
                payment_way_id:data.field.payment_way_id,
                title:data.field.title,
                room:data.field.room,
                hall:data.field.hall,
                toilet:data.field.toilet,
                balcony:data.field.balcony,
                kitchen:data.field.kitchen,
                rent:data.field.rent,
                acreage:data.field.acreage,
                total_floor:data.field.total_floor,
                floor:data.field.floor,
                entire_or_joint:data.field.entire_or_joint,
                is_optimization:data.field.is_optimization,
                is_key:data.field.is_key,
                is_elevator:data.field.is_elevator,
                is_show:data.field.is_show,
                is_top:data.field.is_top,
                sort:data.field.sort,
                cover:data.field.cover,
                cover_ext:data.field.cover_ext,
                cover_size:data.field.cover_size,
                trading_time:data.field.trading_time,
                banner_img:img_arr,
                banner_img_ext:img_ext_arr,
                banner_img_size:img_size_arr,
                label_id:label_id_arr,
                configuration_id:configuration_id_arr,
                is_deal:'{{$is_deal}}',
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
