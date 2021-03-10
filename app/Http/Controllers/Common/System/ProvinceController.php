<?php

namespace App\Http\Controllers\Common\System;
use App\Http\Controllers\Common\BaseController;
use Illuminate\Support\Facades\DB;

class ProvinceController extends BaseController
{
    public $table_name = 'system_province';
    public $table_pk = 'province_id';

    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 列表数据
     */
    public function GetList()
    {
        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.is_show','=',1],
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
     * 根据名称获取详情
     * @param string $province_name
     */
    public function AccordingNameGetInfo($province_name)
    {
        //默认条件
        $where = array(
            [$this->table_name.'.province_name','=',$province_name],
        );

        $info = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.province_name',$this->table_name.'.is_delete')
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
