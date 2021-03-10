<?php

namespace App\Rules\Admin\System;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Administrator extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'administrator_name' => 'bail|required|max:20|min:2',
            'login_account' => 'bail|required|max:32|min:2',
            'login_password' => 'bail|required|max:32|min:6',
            'confirm_password' => 'bail|required|max:32|min:6',
            'role_id' => 'bail|required',
            'phone' => 'bail|required|max:11',
            'email' => 'bail|required|max:255',
            'sex' => 'bail|required',
        );
        $messages = [
            'administrator_name.required' => '请填写管理员名称',
            'administrator_name.max' => '管理员名称最长20个字符',
            'administrator_name.min' => '管理员名称最少2个字符',
            'login_account.required' => '请填写登陆账号',
            'login_account.max' => '登陆账号最长32个字符',
            'login_account.min' => '登陆账号最少2个字符',
            'login_password.required' => '请填写登陆密码',
            'login_password.max' => '登陆密码最长32个字符',
            'login_password.min' => '登陆密码最少6个字符',
            'confirm_password.required' => '请填写确认密码',
            'confirm_password.max' => '确认密码最长32个字符',
            'confirm_password.min' => '确认密码最少6个字符',
            'role_id.required' => '请选择角色',
            'phone.required' => '请填写手机号码',
            'phone.max' => '手机号码最长11个字符',
            'email.required' => '请填写Email',
            'email.max' => 'Email最长255个字符',
            'sex.required' => '请选择性别',
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
            'administrator_name' => 'bail|required|max:20|min:2',
            'role_id' => 'bail|required',
            'phone' => 'bail|required|max:11',
            'email' => 'bail|required|max:255',
            'sex' => 'bail|required',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'administrator_name.required' => '请填写管理员名称',
            'administrator_name.max' => '管理员名称最长20个字符',
            'administrator_name.min' => '管理员名称最少2个字符',
            'role_id.required' => '请选择角色',
            'phone.required' => '请填写手机号码',
            'phone.max' => '手机号码最长11个字符',
            'email.required' => '请填写Email',
            'email.max' => 'Email最长255个字符',
            'sex.required' => '请选择性别',
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
    public function EditAccount($data)
    {
        $rules = array(
            'id' => 'bail|required|integer',
            'login_account' => 'bail|required|max:32|min:2',
            'login_password' => 'bail|required|max:32|min:6',
        );
        $messages = [
            'id.required' => '请选择记录',
            'id.integer' => 'id必须是整数',
            'login_account.required' => '请填写登陆账号',
            'login_account.max' => '登陆账号最长32个字符',
            'login_account.min' => '登陆账号最少2个字符',
            'login_password.required' => '请填写登陆密码',
            'login_password.max' => '登陆密码最长32个字符',
            'login_password.min' => '登陆密码最少6个字符',
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
