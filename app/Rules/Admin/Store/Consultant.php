<?php

namespace App\Rules\Admin\Store;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Consultant extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'consultant_name' => 'bail|required|max:20|min:2',
            'store_id' => 'bail|required|integer',
            'user_id' => 'bail|required|integer',
            'position_id' => 'bail|required|integer',
            'phone' => 'bail|required|max:11',
            'sex' => 'bail|required',
            'head' => 'bail|required',
            'induction_date' => 'bail|required|date',
        );
        $messages = [
            'consultant_name.required' => '请填写顾问姓名',
            'consultant_name.max' => '顾问姓名最长20个字符',
            'consultant_name.min' => '顾问姓名最少2个字符',
            'store_id.required' => '请选择门店',
            'store_id.integer' => '门店id只能是整数',
            'user_id.required' => '请选择用户',
            'user_id.integer' => '用户id只能是整数',
            'position_id.required' => '请选择职位',
            'position_id.integer' => '职位id只能是整数',
            'phone.required' => '请填写手机号码',
            'phone.max' => '手机号码最长11个字符',
            'sex.required' => '请选择性别',
            'head.required' => '请上传头像',
            'induction_date.required' => '请选择入职日期',
            'induction_date.date' => '入职日期格式错误',
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
            'consultant_name' => 'bail|required|max:20|min:2',
            'store_id' => 'bail|required|integer',
            'user_id' => 'bail|required|integer',
            'position_id' => 'bail|required|integer',
            'phone' => 'bail|required|max:11',
            'sex' => 'bail|required',
            'head' => 'bail|required',
            'induction_date' => 'bail|required|date',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'consultant_name.required' => '请填写顾问姓名',
            'consultant_name.max' => '顾问姓名最长20个字符',
            'consultant_name.min' => '顾问姓名最少2个字符',
            'store_id.required' => '请选择门店',
            'store_id.integer' => '门店id只能是整数',
            'user_id.required' => '请选择用户',
            'user_id.integer' => '用户id只能是整数',
            'position_id.required' => '请选择职位',
            'position_id.integer' => '职位id只能是整数',
            'phone.required' => '请填写手机号码',
            'phone.max' => '手机号码最长11个字符',
            'sex.required' => '请选择性别',
            'head.required' => '请上传头像',
            'induction_date.required' => '请选择入职日期',
            'induction_date.date' => '入职日期格式错误',
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
