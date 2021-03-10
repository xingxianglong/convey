<?php

namespace App\Rules\Admin\House;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Classify extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'classify_name' => 'bail|required|max:8',
            'icon' => 'bail|required',
            'sort' => 'bail|min:1|max:127|integer',
        );
        $messages = [
            'classify_name.required' => '请填写分类名称',
            'classify_name.max' => '分类名称最长8个字符',
            'icon.required' => '请上传图标',
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
            'classify_name' => 'bail|required|max:8',
            'icon' => 'bail|required',
            'sort' => 'bail|min:1|max:127|integer',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'classify_name.required' => '请填写分类名称',
            'classify_name.max' => '分类名称最长8个字符',
            'icon.required' => '请上传图标',
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
