<?php

namespace App\Rules\Api\User;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Reservation extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'house_id' => 'bail|required|integer',
            'nickname' => 'bail|required|string|min:2|max:20',
            'phone' => 'bail|required',
            'look_house_begin_time' => 'bail|required|date',
            'look_house_ent_time' => 'bail|required|date',
            'note' => 'bail|string|max:200',
        );
        $messages = [
            'house_id.required' => '请传入house_id',
            'house_id.integer' => 'house_id只能是整数',
            'nickname.required' => '请填写姓名',
            'nickname.string' => '姓名只能是字符串',
            'nickname.min' => '姓名最少2个字符串',
            'nickname.max' => '姓名最多20个字符串',
            'phone.required' => '请填写手机号码',
            'look_house_begin_time.required' => '请选择看房开始时间',
            'look_house_begin_time.date' => '看房开始时间格式错误',
            'look_house_ent_time.required' => '请选择看房结束时间',
            'look_house_ent_time.date' => '看房结束时间格式错误',
            'note.string' => '备注只能是字符串',
            'note.max' => '备注最多200个字符串',
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

        if(strtotime($data['look_house_begin_time']) > strtotime($data['look_house_ent_time']))
        {
            return '看房开始时间不能大于结束时间';
        }
        elseif(strtotime($data['look_house_ent_time']) <= time())
        {
            return '看房结束时间不能小于现在';
        }

        return '';
    }


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function GetPage($data)
    {
        $rules = array(
            'page' => 'bail|integer',
            'limit' => 'bail|integer',
        );
        $messages = [
            'page.integer' => 'page只能是整数',
            'limit.integer' => 'limit只能是整数',
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
    public function GetConsultantPage($data)
    {
        $rules = array(
            'consultant_id' => 'bail|required|integer',
            'page' => 'bail|integer',
            'limit' => 'bail|integer',
        );
        $messages = [
            'consultant_id.required' => '请传入顾问id',
            'consultant_id.integer' => '顾问id只能是整数',
            'page.integer' => 'page只能是整数',
            'limit.integer' => 'limit只能是整数',
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
