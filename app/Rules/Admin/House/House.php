<?php

namespace App\Rules\Admin\House;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class House extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'store_id' => 'bail|required|integer',
            'consultant_id' => 'bail|required|integer',
            'community_id' => 'bail|required|integer',
            'title' => 'bail|required|max:50|min:2',
            'rent' => 'bail|required|numeric|min:0.01|max:99999',
            'acreage' => 'bail|required|numeric|min:0.01|max:99999',
            'classify_id' => 'bail|required|integer',
            'direction_id' => 'bail|required|integer',
            'decorate_id' => 'bail|required|integer',
            'payment_way_id' => 'bail|required|integer',
            'cover' => 'bail|required',
            'room' => 'bail|required|integer',
            'hall' => 'bail|required|integer',
            'toilet' => 'bail|required|integer',
            'balcony' => 'bail|required|integer',
            'kitchen' => 'bail|required|integer',
            'total_floor' => 'bail|required|integer|min:1|max:99',
            'floor' => 'bail|required|integer|min:1|max:99|lte:total_floor',
            'is_key' => 'bail|required|integer',
            'is_elevator' => 'bail|required|integer',
            'entire_or_joint' => 'bail|required|integer',
            'is_optimization' => 'bail|required|integer',
            'sort' => 'bail|min:1|max:127|integer',
            'banner_img' => 'bail|required|array',
            'label_id' => 'bail|required|array',
            'configuration_id' => 'bail|required|array',
            'is_deal' => 'bail|required|integer',
        );
        $messages = [
            'store_id.required' => '请选择门店',
            'store_id.integer' => '门店id只能是整数',
            'consultant_id.required' => '请选择顾问',
            'consultant_id.integer' => '顾问id只能是整数',
            'community_id.required' => '请选择小区',
            'community_id.integer' => '小区id只能是整数',
            'classify_id.required' => '请选择分类',
            'classify_id.integer' => '分类id只能是整数',
            'direction_id.required' => '请选择朝向',
            'direction_id.integer' => '朝向id只能是整数',
            'decorate_id.required' => '请选择装修',
            'decorate_id.integer' => '装修id只能是整数',
            'payment_way_id.required' => '请选择付款方式',
            'payment_way_id.integer' => '付款方式id只能是整数',
            'title.required' => '请填写标题',
            'title.max' => '标题最长50个字符',
            'title.min' => '标题最少2个字符',
            'cover.required' => '请上传封面图',
            'room.required' => '请选择室',
            'room.integer' => '室只能是整数',
            'hall.required' => '请选择厅',
            'hall.integer' => '厅只能是整数',
            'toilet.required' => '请选择卫',
            'toilet.integer' => '卫只能是整数',
            'balcony.required' => '请选择阳台',
            'balcony.integer' => '阳台只能是整数',
            'kitchen.required' => '请选择厨',
            'kitchen.integer' => '厨只能是整数',
            'rent.required' => '请输入租金',
            'rent.numeric' => '租金必须是数字',
            'rent.min' => '租金不能小于0.01',
            'rent.max' => '租金不能大于99999',
            'acreage.required' => '请输入面积',
            'acreage.numeric' => '面积必须是数字',
            'acreage.min' => '面积不能小于0.01',
            'acreage.max' => '面积不能大于99999',
            'total_floor.required' => '请选择总楼层',
            'total_floor.integer' => '总楼层只能是整数',
            'total_floor.min' => '总楼层不能小于1',
            'total_floor.max' => '总楼层不能大于99',
            'floor.required' => '请选择楼层',
            'floor.integer' => '楼层只能是整数',
            'floor.min' => '楼层不能小于1',
            'floor.max' => '楼层不能大于99',
            'floor.lte' => '楼层不能大于总楼层',
            'is_key.required' => '请选择有没有钥匙',
            'is_key.integer' => '钥匙参数只能是整数',
            'is_elevator.required' => '请选择有没有电梯',
            'is_elevator.integer' => '电梯参数只能是整数',
            'entire_or_joint.required' => '请选择整租or合租',
            'entire_or_joint.integer' => '整合or合租参数只能是整数',
            'is_optimization.required' => '请选择优选好房',
            'is_optimization.integer' => '优选好房参数只能是整数',
            'sort.integer' => '排序号只能是整数',
            'sort.min' => '排序号最小为1',
            'sort.max' => '排序号最大为127',
            'banner_img.required' => '请上传轮播图',
            'banner_img.array' => '轮播图参数只能是数组',
            'label_id.required' => '请选择标签',
            'label_id.array' => '标签参数只能是数组',
            'configuration_id.required' => '请选择配置',
            'configuration_id.array' => '配置参数只能是数组',
            'is_deal.required' => '请传入成交动态房源',
            'is_deal.integer' => '成交动态房源只能是整数',
        ];

        $validator = Validator::make($data, $rules,$messages);

        $errors = $validator->errors();

        foreach ($errors->all() as $message)
        {
            if(!empty($message))
            {
                return $message;
            }
        }

        return '';
    }


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Edit($data)
    {
        $rules = array(
            'id' => 'bail|required|integer',
            'store_id' => 'bail|required|integer',
            'consultant_id' => 'bail|required|integer',
            'community_id' => 'bail|required|integer',
            'title' => 'bail|required|max:50|min:2',
            'rent' => 'bail|required|numeric|min:0.01|max:99999',
            'acreage' => 'bail|required|numeric|min:0.01|max:99999',
            'classify_id' => 'bail|required|integer',
            'direction_id' => 'bail|required|integer',
            'decorate_id' => 'bail|required|integer',
            'payment_way_id' => 'bail|required|integer',
            'cover' => 'bail|required',
            'room' => 'bail|required|integer',
            'hall' => 'bail|required|integer',
            'toilet' => 'bail|required|integer',
            'balcony' => 'bail|required|integer',
            'kitchen' => 'bail|required|integer',
            'total_floor' => 'bail|required|integer|min:1|max:99',
            'floor' => 'bail|required|integer|min:1|max:99|lte:total_floor',
            'is_key' => 'bail|required|integer',
            'is_elevator' => 'bail|required|integer',
            'entire_or_joint' => 'bail|required|integer',
            'is_optimization' => 'bail|required|integer',
            'sort' => 'bail|min:1|max:127|integer',
            'banner_img' => 'bail|required|array',
            'label_id' => 'bail|required|array',
            'configuration_id' => 'bail|required|array',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'store_id.required' => '请选择门店',
            'store_id.integer' => '门店id只能是整数',
            'consultant_id.required' => '请选择顾问',
            'consultant_id.integer' => '顾问id只能是整数',
            'community_id.required' => '请选择小区',
            'community_id.integer' => '小区id只能是整数',
            'classify_id.required' => '请选择分类',
            'classify_id.integer' => '分类id只能是整数',
            'direction_id.required' => '请选择朝向',
            'direction_id.integer' => '朝向id只能是整数',
            'decorate_id.required' => '请选择装修',
            'decorate_id.integer' => '装修id只能是整数',
            'payment_way_id.required' => '请选择付款方式',
            'payment_way_id.integer' => '付款方式id只能是整数',
            'title.required' => '请填写标题',
            'title.max' => '标题最长50个字符',
            'title.min' => '标题最少2个字符',
            'cover.required' => '请上传封面图',
            'room.required' => '请选择室',
            'room.integer' => '室只能是整数',
            'hall.required' => '请选择厅',
            'hall.integer' => '厅只能是整数',
            'toilet.required' => '请选择卫',
            'toilet.integer' => '卫只能是整数',
            'balcony.required' => '请选择阳台',
            'balcony.integer' => '阳台只能是整数',
            'kitchen.required' => '请选择厨',
            'kitchen.integer' => '厨只能是整数',
            'rent.required' => '请输入租金',
            'rent.numeric' => '租金必须是数字',
            'rent.min' => '租金不能小于0.01',
            'rent.max' => '租金不能大于99999',
            'acreage.required' => '请输入面积',
            'acreage.numeric' => '面积必须是数字',
            'acreage.min' => '面积不能小于0.01',
            'acreage.max' => '面积不能大于99999',
            'total_floor.required' => '请选择总楼层',
            'total_floor.integer' => '总楼层只能是整数',
            'total_floor.min' => '总楼层不能小于1',
            'total_floor.max' => '总楼层不能大于99',
            'floor.required' => '请选择楼层',
            'floor.integer' => '楼层只能是整数',
            'floor.min' => '楼层不能小于1',
            'floor.max' => '楼层不能大于99',
            'floor.lte' => '楼层不能大于总楼层',
            'is_key.required' => '请选择有没有钥匙',
            'is_key.integer' => '钥匙参数只能是整数',
            'is_elevator.required' => '请选择有没有电梯',
            'is_elevator.integer' => '电梯参数只能是整数',
            'entire_or_joint.required' => '请选择整租or合租',
            'entire_or_joint.integer' => '整合or合租参数只能是整数',
            'is_optimization.required' => '请选择优选好房',
            'is_optimization.integer' => '优选好房参数只能是整数',
            'sort.integer' => '排序号只能是整数',
            'sort.min' => '排序号最小为1',
            'sort.max' => '排序号最大为127',
            'banner_img.required' => '请上传轮播图',
            'banner_img.array' => '轮播图参数只能是数组',
            'label_id.required' => '请选择标签',
            'label_id.array' => '标签参数只能是数组',
            'configuration_id.required' => '请选择配置',
            'configuration_id.array' => '配置参数只能是数组',
        ];

        $validator = Validator::make($data, $rules,$messages);

        $errors = $validator->errors();

        foreach ($errors->all() as $message)
        {
            if(!empty($message))
            {
                return $message;
            }
        }

        return '';
    }


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Delete($data)
    {
        $rules = array(
            'id' => 'bail|required',
        );
        $messages = [
            'id.required' => '请选择记录',
        ];

        $validator = Validator::make($data, $rules,$messages);

        $errors = $validator->errors();

        foreach ($errors->all() as $message)
        {
            if(!empty($message))
            {
                return $message;
            }
        }

        return '';
    }
}
