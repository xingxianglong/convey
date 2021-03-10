<?php

namespace App\Rules\Api\User;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Consult extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Add($data)
    {
        $rules = array(
            'consultant_id' => 'bail|required|integer',
        );
        $messages = [
            'consultant_id.required' => '请传入consultant_id',
            'consultant_id.integer' => 'consultant_id只能是整数',
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

}
