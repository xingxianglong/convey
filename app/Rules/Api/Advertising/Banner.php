<?php

namespace App\Rules\Api\Advertising;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;

class Banner extends Facade
{


    /**
     * @param array $data 数据
     *
     * @return string
     */
    public function GetList($data)
    {
        $rules = array(
            'location_id' => 'bail|required|integer',
        );
        $messages = [
            'location_id.required' => '请传入location_id',
            'location_id.integer' => 'location_id只能是整数',
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
