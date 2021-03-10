<?php

namespace App\Rules\Api\User;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class User extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function XcxAccredit($data)
    {
        $rules = array(
            'js_code' => 'bail|required',
            'user_name' => 'bail|required',
            'head' => 'bail|required|string',
        );
        $messages = [
            'js_code.required' => '请传入js_code',
            'user_name.required' => '请传入微信用户昵称',
            'head.required' => '请传入微信用户头像',
            'head.string' => '用户头像只能是字符串',
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
