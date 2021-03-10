<?php

namespace App\Rules\Admin\Advertising;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Banner extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'location_id' => 'bail|required|integer',
            'img' => 'bail|required',
            'sort' => 'bail|min:1|max:127|integer',
        );
        $messages = [
            'location_id.required' => '请选择显示位置',
            'location_id.integer' => '显示位置只能是整数',
            'img.required' => '请上传图片',
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
            'location_id' => 'bail|required|integer',
            'img' => 'bail|required',
            'sort' => 'bail|min:1|max:127|integer',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'location_id.required' => '请选择显示位置',
            'location_id.integer' => '显示位置只能是整数',
            'img.required' => '请上传图片',
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
