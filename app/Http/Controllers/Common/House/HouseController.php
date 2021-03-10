<?php

namespace App\Http\Controllers\Common\House;

use App\Http\Controllers\Common\BaseController;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class HouseController extends BaseController
{
    public $table_name = 'web_house';
    public $table_pk = 'house_id';

    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 生成唯一编号
     *
     * @return array
     */
    public function GenerateCode(){
        //编号规则：数字+字母 随机6位 例如：8MBRQM
        $code_array = array(
            0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
        );

        $random_keys = array();
        for($i = 0; $i < 6; $i++)
        {
            $keys = array_rand($code_array,1);

            array_push($random_keys,$code_array[$keys]);
        }

        //数组转成字符串
        $code = implode('',$random_keys);

        //查询是否存在
        $where = array(
            ['house_code','=',$code]
        );
        $count = DB::table($this->table_name)
            ->where($where)
            ->count();
        //不存在直接返回
        if($count <= 0){
            return $code;
        }

        return $this->GenerateCode();
    }

}
