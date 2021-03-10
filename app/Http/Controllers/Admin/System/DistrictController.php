<?php

namespace App\Http\Controllers\Admin\System;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistrictController extends BaseController
{
    public $table_name = 'system_district';
    public $table_pk = 'district_id';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 根据区域获取数据
     * @param int $city_id
     */
    public function AccordingCityGetData($city_id=0)
    {
        if(empty($city_id))
        {
            $request = Request();
            $all = $request->all();

            if(!isset($all['city_id']) || empty($all['city_id']))
            {
                return $this->common_class->ajaxDataReturnFormat(1,'请传入城市id');
            }

            $city_id = $all['city_id'];
        }


        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.is_show','=',1],
            [$this->table_name.'.city_id','=',$city_id],
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
