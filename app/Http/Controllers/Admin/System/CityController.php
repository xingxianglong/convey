<?php

namespace App\Http\Controllers\Admin\System;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends BaseController
{
    public $table_name = 'system_city';
    public $table_pk = 'city_id';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 根据省份获取数据
     * @param int $province_id
     */
    public function AccordingProvinceGetData($province_id=0)
    {
        if(empty($province_id))
        {
            $request = Request();
            $all = $request->all();

            if(!isset($all['province_id']) || empty($all['province_id']))
            {
                return $this->common_class->ajaxDataReturnFormat(1,'请传入省份id');
            }

            $province_id = $all['province_id'];
        }

        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.is_show','=',1],
            [$this->table_name.'.province_id','=',$province_id],
        );

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id')
            ->where($where)
            ->orderBy($this->table_name.'.is_top','asc')
            ->orderBy($this->table_name.'.sort','asc')
            ->orderBy($this->table_name.'.'.$this->table_pk,'asc')
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data);
    }


    /**
     * 详情
     * @param int $id
     */
    public function GetInfo($id)
    {
        //默认条件
        $where = array(
            [$this->table_name.'.'.$this->table_pk,'=',$id],
        );

        $info = DB::table($this->table_name)
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到记录');
        }
        elseif($info->is_delete == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'记录已被删除');
        }

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$info);
    }

}
