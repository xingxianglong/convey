<?php

namespace App\Rules\Admin\Store;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Store extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'store_name' => 'bail|required|max:8',
            'cover' => 'bail|required',
            'province_id' => 'bail|required|integer',
            'city_id' => 'bail|required|integer',
            'district_id' => 'bail|required|integer',
            'detail_address' => 'bail|required|max:100',
        );
        $messages = [
            'store_name.required' => '请填写门店名称',
            'store_name.max' => '门店名称最长8个字符',
            'cover.required' => '请上传封面图',
            'province_id.required' => '请选择省份',
            'province_id.integer' => '省份id只能是整数',
            'city_id.required' => '请选择城市',
            'city_id.integer' => '城市id只能是整数',
            'district_id.required' => '请选择区域',
            'district_id.integer' => '区域id只能是整数',
            'detail_address.required' => '请填写详细地址',
            'detail_address.max' => '详细地址最长100个字符',
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
            'store_name' => 'bail|required|max:8',
            'cover' => 'bail|required',
            'province_id' => 'bail|required|integer',
            'city_id' => 'bail|required|integer',
            'district_id' => 'bail|required|integer',
            'detail_address' => 'bail|required|max:100',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'store_name.required' => '请填写门店名称',
            'store_name.max' => '门店名称最长8个字符',
            'cover.required' => '请上传封面图',
            'province_id.required' => '请选择省份',
            'province_id.integer' => '省份id只能是整数',
            'city_id.required' => '请选择城市',
            'city_id.integer' => '城市id只能是整数',
            'district_id.required' => '请选择区域',
            'district_id.integer' => '区域id只能是整数',
            'detail_address.required' => '请填写详细地址',
            'detail_address.max' => '详细地址最长100个字符',
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
