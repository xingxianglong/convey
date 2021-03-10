<?php

namespace App\Http\Controllers\Common\System;

use App\Http\Controllers\Common\BaseController;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class ConfigController extends BaseController
{
    public $table_name = 'system_config';
    public $table_pk = 'config_id';

    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 根据键名获取值
     *
     * @return string
     */
    public function AccordingKeyGetValue($key)
    {
        if(empty($key))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'请传入key');
        }

        $where = array(
            ['_key','=',$key],
        );
        $info = DB::table($this->table_name)
            ->select($this->table_pk.' as id','_key','_value','img','note','is_delete')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到系统配置');
        }
        elseif($info->is_delete == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该系统配置已被删除');
        }

        $info->img = isset($info->img) ? explode(',',$info->img) : array();

        unset($info->is_delete);

        $info = (array)$info;

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$info);
    }

}
