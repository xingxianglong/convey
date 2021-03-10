<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\House\LabelMappingController;
use App\Rules\Api\Store\Store as RulesStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends BaseController
{
    public $table_name = 'web_store';
    public $table_pk = 'store_id';

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
     * @param RulesStore $validator
     */
    public function GetInfo(Request $request,RulesStore $validator)
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
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.store_name',$this->table_name.'.cover',$this->table_name.'.note',$this->table_name.'.is_delete','system_province.province_name','system_city.city_name','system_district.district_name',$this->table_name.'.detail_address')
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
            return $this->common_class->ajaxDataReturnFormat(1,'该门店已删除');
        }
        unset($info->is_delete);

        //门店人数
        $consultant_res = $this->GetConsultantCount($info->id);
        $info->consultant_count = $consultant_res['data']['count'];

        //在租房源
        $house_res = $this->GetHouseCount($info->id);
        $info->house_count = $house_res['data']['count'];

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$info);
    }


    /**
     * 获取房源总数
     * @param string $store_id 门店id
     */
    public function GetConsultantCount($store_id)
    {
        $where = array(
            ['web_store_consultant.is_delete','=',0],
            ['web_store_consultant.store_id','=',$store_id],
        );
        $count = DB::table('web_store_consultant')
            ->where($where)
            ->count();

        $data = array(
            'count' => $count,
        );

        return $this->common_class->ajaxDataReturnFormat(0,'成功',$data);
    }


    /**
     * 获取房源总数
     * @param string $store_id 门店id
     */
    public function GetHouseCount($store_id)
    {
        $where = array(
            ['web_house.is_delete','=',0],
            ['web_house.is_show','=',1],
            ['web_house.is_deal','=',2],
            ['web_house.store_id','=',$store_id],
        );
        $count = DB::table('web_house')
            ->where($where)
            ->count();

        $data = array(
            'count' => $count,
        );

        return $this->common_class->ajaxDataReturnFormat(0,'成功',$data);
    }


    /**
     * 在租房源
     * @param Request $request
     * @param RulesStore $validator
     */
    public function GetLeaseHousePage(Request $request,RulesStore $validator)
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
            ['web_house.store_id','=',$id],
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


    /**
     * 合作伙伴
     * @param Request $request
     * @param RulesStore $validator
     */
    public function GetConsultantPage(Request $request,RulesStore $validator)
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
            ['web_store_consultant.is_delete','=',0],
            ['web_store_consultant.store_id','=',$id],
        );

        $data = DB::table('web_store_consultant')
            ->select('web_store_consultant.consultant_id as id','web_store_consultant.consultant_name','web_store_consultant.head','web_store_consultant.induction_date')
            ->where($where)
            ->orderBy('web_store_consultant.consultant_id','desc')
            ->paginate($limit);

        $consultant = new ConsultantController($request);
        foreach($data as $k => $v)
        {
            //在职日期
            $data[$k]->work_date = $consultant->WorkDate($v->induction_date);
            unset($data[$k]->induction_date);

            //房源出租
            $house_res = $consultant->GetHouseData($v->id);
            $data[$k]->house_count = $house_res['data']['entire_count'] + $house_res['data']['joint_count'];
        }

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$data->total(),$data->lastPage());
    }


    /**
     * 成交动态
     * @param Request $request
     * @param RulesStore $validator
     */
    public function GetDealHousePage(Request $request,RulesStore $validator)
    {
        $validator_res = $validator->GetDealHousePage($request->all());
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
            ['web_house.is_deal','=',1],
            ['web_house.store_id','=',$id],
        );

        $data = DB::table('web_house')
            ->select('web_house.house_id as id','web_house.title','web_house.cover','web_house.entire_or_joint','web_house.room','web_house.hall','web_house.toilet','web_house.acreage','web_house.rent','web_house.trading_time','web_store_consultant.consultant_name','web_store_consultant.head as consultant_head')
            ->leftJoin('web_store_consultant','web_house.consultant_id','=','web_store_consultant.consultant_id')
            ->where($where)
            ->orderBy('web_house.is_top','asc')
            ->orderBy('web_house.sort','asc')
            ->orderBy('web_house.house_id','desc')
            ->paginate($limit);

        foreach($data as $k => $v)
        {
            //成交周期
            $time = time() - strtotime($v->trading_time);
            $data[$k]->deal_day = floor($time / 86400);

        }

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$data->total(),$data->lastPage());
    }
}
