<?php

namespace App\Rules\Api\Store;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Consultant extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function GetInfo($data)
    {
        $rules = array(
            'id' => 'bail|required|integer',
        );
        $messages = [
            'id.required' => '请传入id',
            'id.integer' => 'id只能是整数',
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
    public function GetLeaseHousePage($data)
    {
        $rules = array(
            'id' => 'bail|required|integer',
            'page' => 'bail|integer',
            'limit' => 'bail|integer',
        );
        $messages = [
            'id.required' => '请传入id',
            'id.integer' => 'id只能是整数',
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
