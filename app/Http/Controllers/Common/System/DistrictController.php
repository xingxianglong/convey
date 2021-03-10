<?php

namespace App\Http\Controllers\Common\System;
use App\Http\Controllers\Common\BaseController;
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


    /**
     * 根据城市id区域名获取详情
     * @param int $city_id
     * @param string $district_name
     */
    public function AccordingCityIdNameGetInfo($city_id,$district_name)
    {
        //默认条件
        $where = array(
            [$this->table_name.'.city_id','=',$city_id],
            [$this->table_name.'.district_name','=',$district_name],
        );

        $info = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.city_id',$this->table_name.'.district_name',$this->table_name.'.is_delete')
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
        unset($info->is_delete);

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$info);
    }
}
