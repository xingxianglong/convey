<?php

namespace App\Http\Controllers\Admin\Advertising;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerLocationController extends BaseController
{
    public $table_name = 'web_advertising_banner_location';
    public $table_pk = 'location_id';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 列表数据
     *
     * @return array
     */
    public function GetList(){
        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
        );

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.location_name')
            ->where($where)
            ->orderby($this->table_name.'.'.$this->table_pk,'asc')
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data);
    }
}
