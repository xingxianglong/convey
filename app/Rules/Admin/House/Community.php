<?php

namespace App\Rules\Admin\House;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Community extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'community_name' => 'bail|required|min:2|max:50',
            'building_year' => 'bail|required|size:4',
            'building_type_id' => 'bail|required|integer',
            'building_amount' => 'bail|required|integer|min:1|max:127',
            'house_amount' => 'bail|required|integer|min:1|max:9999',
            'property_company' => 'bail|required|min:2|max:50',
            'property_fee' => 'bail|required|numeric|min:0.01|max:9999',
            'developers' => 'bail|required|min:2|max:50',
            'province_id' => 'bail|required|integer',
            'city_id' => 'bail|required|integer',
            'district_id' => 'bail|required|integer',
            'detail_address' => 'bail|required|max:100',
            'second_hand_price' => 'bail|required|numeric|min:0.01|max:999999999',
            'sort' => 'bail|min:1|max:127|integer',
        );
        $messages = [
            'community_name.required' => '请填写小区名称',
            'community_name.min' => '小区名称最少2个字符',
            'community_name.max' => '小区名称最长50个字符',
            'building_year.required' => '请选择建筑年代',
            'building_year.size' => '建筑年代只能为4个字符',
            'building_type_id.required' => '请选择建筑类型',
            'building_type_id.integer' => '建筑类型只能是整数',
            'building_amount.required' => '请填写楼栋总数',
            'building_amount.integer' => '楼栋总数只能是整数',
            'building_amount.min' => '楼栋总数最小为1',
            'building_amount.max' => '楼栋总数最大为127',
            'house_amount.required' => '请填写房屋总数',
            'house_amount.integer' => '房屋总数只能是数字',
            'house_amount.min' => '房屋总数最小为1',
            'house_amount.max' => '房屋总数最大为9999',
            'property_company.required' => '请填写物业公司',
            'property_company.min' => '物业公司最少2个字符',
            'property_company.max' => '物业公司最长50个字符',
            'property_fee.required' => '请填写物业费',
            'property_fee.numeric' => '物业费只能是数字',
            'property_fee.min' => '物业费最小为0.01',
            'property_fee.max' => '物业费最大为9999',
            'developers.required' => '请填写开发商',
            'developers.min' => '开发商最少2个字符',
            'developers.max' => '开发商最长50个字符',
            'province_id.required' => '请选择省份',
            'province_id.integer' => '省份id只能是整数',
            'city_id.required' => '请选择城市',
            'city_id.integer' => '城市id只能是整数',
            'district_id.required' => '请选择区域',
            'district_id.integer' => '区域id只能是整数',
            'detail_address.required' => '请填写详细地址',
            'detail_address.max' => '详细地址最长100个字符',
            'second_hand_price.required' => '请填写二手房价',
            'second_hand_price.numeric' => '二手房价只能是数字',
            'second_hand_price.min' => '二手房价最小为0.01',
            'second_hand_price.max' => '二手房价最大为999999999',
            'sort.integer' => '排序号只能是整数',
            'sort.min' => '排序号最小为1',
            'sort.max' => '排序号最大为127',
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
            'community_name' => 'bail|required|min:2|max:50',
            'building_year' => 'bail|required|size:4',
            'building_type_id' => 'bail|required|integer',
            'building_amount' => 'bail|required|integer|min:1|max:127',
            'house_amount' => 'bail|required|integer|min:1|max:9999',
            'property_company' => 'bail|required|min:2|max:50',
            'property_fee' => 'bail|required|numeric|min:0.01|max:9999',
            'developers' => 'bail|required|min:2|max:50',
            'province_id' => 'bail|required|integer',
            'city_id' => 'bail|required|integer',
            'district_id' => 'bail|required|integer',
            'detail_address' => 'bail|required|max:100',
            'second_hand_price' => 'bail|required|numeric|min:0.01|max:999999999',
            'sort' => 'bail|min:1|max:127|integer',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'community_name.required' => '请填写小区名称',
            'community_name.min' => '小区名称最少2个字符',
            'community_name.max' => '小区名称最长50个字符',
            'building_year.required' => '请选择建筑年代',
            'building_year.size' => '建筑年代只能为4个字符',
            'building_type_id.required' => '请选择建筑类型',
            'building_type_id.integer' => '建筑类型只能是整数',
            'building_amount.required' => '请填写楼栋总数',
            'building_amount.integer' => '楼栋总数只能是整数',
            'building_amount.min' => '楼栋总数最小为1',
            'building_amount.max' => '楼栋总数最大为127',
            'house_amount.required' => '请填写房屋总数',
            'house_amount.integer' => '房屋总数只能是数字',
            'house_amount.min' => '房屋总数最小为1',
            'house_amount.max' => '房屋总数最大为9999',
            'property_company.required' => '请填写物业公司',
            'property_company.min' => '物业公司最少2个字符',
            'property_company.max' => '物业公司最长50个字符',
            'property_fee.required' => '请填写物业费',
            'property_fee.numeric' => '物业费只能是数字',
            'property_fee.min' => '物业费最小为0.01',
            'property_fee.max' => '物业费最大为9999',
            'developers.required' => '请填写开发商',
            'developers.min' => '开发商最少2个字符',
            'developers.max' => '开发商最长50个字符',
            'province_id.required' => '请选择省份',
            'province_id.integer' => '省份id只能是整数',
            'city_id.required' => '请选择城市',
            'city_id.integer' => '城市id只能是整数',
            'district_id.required' => '请选择区域',
            'district_id.integer' => '区域id只能是整数',
            'detail_address.required' => '请填写详细地址',
            'detail_address.max' => '详细地址最长100个字符',
            'second_hand_price.required' => '请填写二手房价',
            'second_hand_price.numeric' => '二手房价只能是数字',
            'second_hand_price.min' => '二手房价最小为0.01',
            'second_hand_price.max' => '二手房价最大为999999999',
            'sort.integer' => '排序号只能是整数',
            'sort.min' => '排序号最小为1',
            'sort.max' => '排序号最大为127',
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
