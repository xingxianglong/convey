<?php

namespace App\Rules\Api\User;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Collect extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function Update($data)
    {
        $rules = array(
            'house_id' => 'bail|required|integer',
        );
        $messages = [
            'house_id.required' => '请传入house_id',
            'house_id.integer' => 'house_id只能是整数',
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
