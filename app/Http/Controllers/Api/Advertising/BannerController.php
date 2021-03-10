<?php

namespace App\Http\Controllers\Api\Advertising;

use App\Http\Controllers\Api\BaseController;
use App\Rules\Api\Advertising\Banner as RulesBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends BaseController
{
    public $table_name = 'web_advertising_banner';
    public $table_pk = 'banner_id';

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
     * @param RulesBanner $validator
     */
    public function GetList(Request $request,RulesBanner $validator)
    {
        $validator_res = $validator->GetList($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $location_id = $all['location_id']; //位置id

        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.location_id','=',$location_id],
        );
        $data = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.img')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$data);
    }
}
