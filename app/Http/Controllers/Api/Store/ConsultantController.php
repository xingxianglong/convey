<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\House\LabelMappingController;
use App\Rules\Api\Store\Consultant as RulesConsultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultantController extends BaseController
{
    public $table_name = 'web_store_consultant';
    public $table_pk = 'consultant_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 详情
     * @param Request $request
     * @param RulesConsultant $validator
     */
    public function GetInfo(Request $request,RulesConsultant $validator)
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
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.consultant_name',$this->table_name.'.head',$this->table_name.'.phone',$this->table_name.'.is_delete',$this->table_name.'.induction_date',$this->table_name.'.store_id','web_store.store_name','web_store_consultant_position.position_name','system_province.province_name','system_city.city_name','system_district.district_name','web_store.detail_address')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant_position',$this->table_name.'.position_id','=','web_store_consultant_position.position_id')
            ->leftJoin('system_province','web_store.province_id','=','system_province.province_id')
            ->leftJoin('system_city','web_store.city_id','=','system_city.city_id')
            ->leftJoin('system_district','web_store.district_id','=','system_district.district_id')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到记录');
        }
        elseif($info->is_delete != 0)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该顾问已删除');
        }
        unset($info->is_delete);

        //在职日期
        $info->work_date = $this->WorkDate($info->induction_date);
        unset($info->induction_date);

        //在租房源
        $house_res = $this->GetHouseData($info->id);
        $info->entire_count = $house_res['data']['entire_count'];
        $info->joint_count = $house_res['data']['joint_count'];

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$info);
    }


    /**
     * 在职日期
     * @param string $induction_date 入职日期
     */
    public function WorkDate($induction_date)
    {
        //在职日期
        $year = date('Y') - date('Y',strtotime($induction_date));
        if($year > 0)
        {
            $month = (date('m') + ($year * 12)) - date('m',strtotime($induction_date));
        }
        else
        {
            $month = date('m') - date('m',strtotime($induction_date));
        }

        $work_date = '';
        $work_month = (int)fmod($month,12);
        if($month >= 12)
        {
            $work_year = (int)floor($month / 12);

            $work_date .= $work_year.'年';
            if($work_month > 0)
                $work_date .= $work_month.'个月';
        }
        else
        {
            if($work_month == 0)
                $work_date .= '不到一个月';
            else
                $work_date .= $work_month.'个月';
        }

        return $work_date;
    }


    /**
     * 获取房源数据
     * @param string $consultant_id 顾问id
     */
    public function GetHouseData($consultant_id)
    {
        $where = array(
            ['web_house.is_delete','=',0],
            ['web_house.is_show','=',1],
            ['web_house.is_deal','=',2],
            ['web_house.consultant_id','=',$consultant_id],
            ['web_house.entire_or_joint','=',1],
        );
        $entire_count = DB::table('web_house')
            ->where($where)
            ->count();

        $where = array(
            ['web_house.is_delete','=',0],
            ['web_house.is_show','=',1],
            ['web_house.is_deal','=',2],
            ['web_house.consultant_id','=',$consultant_id],
            ['web_house.entire_or_joint','=',2],
        );
        $joint_count = DB::table('web_house')
            ->where($where)
            ->count();

        $data = array(
            'entire_count' => $entire_count,
            'joint_count' => $joint_count,
        );

        return $this->common_class->ajaxDataReturnFormat(0,'成功',$data);
    }


    /**
     * 在租房源
     * @param Request $request
     * @param RulesConsultant $validator
     */
    public function GetLeaseHousePage(Request $request,RulesConsultant $validator)
    {
        $validator_res = $validator->GetLeaseHousePage($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //记录id
        $limit = isset($all['limit']) ? $all['limit'] : 10; //每页数量

        //默认条件
        $where = array(
            ['web_house.is_delete','=',0],
            ['web_house.is_show','=',1],
            ['web_house.is_deal','=',2],
            ['web_house.consultant_id','=',$id],
        );

        $data = DB::table('web_house')
            ->select('web_house.house_id as id','web_house.title','web_house.cover','web_house.is_optimization','web_house.rent','web_house.acreage','web_house.room','web_house.hall','web_house.toilet')
            ->leftJoin('web_house_community','web_house.community_id','=','web_house_community.community_id')
            ->where($where)
            ->orderBy('web_house.is_top','asc')
            ->orderBy('web_house.sort','asc')
            ->orderBy('web_house.house_id','desc')
            ->paginate($limit);

        $label_mapping = new LabelMappingController($request);
        foreach($data as $k => $v)
        {
            //标签
            $lavel_res = $label_mapping->AccordingHouseGetData($v->id,2);
            $data[$k]->label_data = $lavel_res['data'];
        }

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$data->total(),$data->lastPage());
    }
}
