<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentController extends BaseController
{
    public $table_name = 'web_house_rent';
    public $table_pk = 'rent_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 获取数据
     * @param Request $request
     */
    public function GetList(Request $request)
    {
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.is_show','=',1],
        );
        $data = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.min_price',$this->table_name.'.max_price')
            ->where($where)
            ->orderBy($this->table_name.'.is_top','asc')
            ->orderBy($this->table_name.'.sort','asc')
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$data);
    }
}
