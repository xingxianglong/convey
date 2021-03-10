<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Api\BaseController;
use App\Rules\Api\House\Community as RulesCommunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityController extends BaseController
{
    public $table_name = 'web_house_community';
    public $table_pk = 'community_id';

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
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.community_name')
            ->where($where)
            ->orderBy($this->table_name.'.is_top','asc')
            ->orderBy($this->table_name.'.sort','asc')
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$data);
    }


    /**
     * 详情
     * @param Request $request
     * @param RulesCommunity $validator
     */
    public function GetInfo(Request $request,RulesCommunity $validator)
    {
        $validator_res = $validator->GetInfo($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //记录id

        $where = array(
            [$this->table_name.'.'.$this->table_pk,'=',$id]
        );
        $info = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.community_name',$this->table_name.'.building_year','web_house_building_type.type_name as building_type_name',$this->table_name.'.building_amount',$this->table_name.'.house_amount',$this->table_name.'.property_company',$this->table_name.'.property_fee',$this->table_name.'.developers',$this->table_name.'.second_hand_price','system_province.province_name','system_city.city_name','system_district.district_name',$this->table_name.'.detail_address',$this->table_name.'.is_show',$this->table_name.'.is_delete')
            ->leftJoin('web_house_building_type',$this->table_name.'.building_type_id','=','web_house_building_type.type_id')
            ->leftJoin('system_province',$this->table_name.'.province_id','=','system_province.province_id')
            ->leftJoin('system_city',$this->table_name.'.city_id','=','system_city.city_id')
            ->leftJoin('system_district',$this->table_name.'.district_id','=','system_district.district_id')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到记录');
        }
        elseif($info->is_show != 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该小区已下架');
        }
        elseif($info->is_delete != 0)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该小区已删除');
        }
        unset($info->is_show);
        unset($info->is_delete);

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$info);
    }

}
