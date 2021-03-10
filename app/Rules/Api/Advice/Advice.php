<?php

namespace App\Rules\Api\Advice;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Advice extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'phone' => 'bail|required',
            'content' => 'bail|required|string|min:2|max:200',
            'img' => 'bail|nullable|string',
        );
        $messages = [
            'phone.required' => '请输入手机号码',
            'content.required' => '请输入内容',
            'content.string' => '内容必须是字符串',
            'content.min' => '内容最少2个字符',
            'content.max' => '内容最多200个字符',
            'img.string' => '图片必须是字符串',
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
