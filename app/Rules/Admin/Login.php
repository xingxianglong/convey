<?php

namespace App\Rules\Admin;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Login extends Facade
{
    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function validator($data)
    {
        $rules = array(
            'login_account' => 'bail|required|max:32|min:2',
            'login_password' => 'bail|required|max:32|min:6',
        );
        $messages = [
            'login_account.required' => '请填写账号',
            'login_account.max' => '账号最长32个字符',
            'login_account.min' => '账号最少2个字符',
            'login_password.required' => '请填写密码',
            'login_password.max' => '密码最长32个字符',
            'login_password.min' => '密码最少6个字符',
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
