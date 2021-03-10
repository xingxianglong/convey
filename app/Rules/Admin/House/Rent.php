<?php

namespace App\Rules\Admin\House;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Rent extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'min_price' => 'bail|required|numeric|min:0.01|max:99999',
            'max_price' => 'bail|required|numeric|min:0.01|max:99999',
            'sort' => 'bail|min:1|max:127|integer',
        );
        $messages = [
            'min_price.required' => '请输入最小金额',
            'min_price.numeric' => '最小金额必须是数字',
            'min_price.min' => '最小金额不能小于0.01',
            'min_price.max' => '最小金额不能大于99999',
            'max_price.required' => '请输入最大金额',
            'max_price.numeric' => '最大金额必须是数字',
            'max_price.min' => '最小金额不能小于0.01',
            'max_price.max' => '最小金额不能大于99999',
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
            'min_price' => 'bail|required|numeric',
            'max_price' => 'bail|required|numeric',
            'sort' => 'bail|min:1|max:127|integer',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'min_price.required' => '请输入最小金额',
            'min_price.numeric' => '最小金额必须是数字',
            'max_price.required' => '请输入最大金额',
            'max_price.numeric' => '最大金额必须是数字',
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
