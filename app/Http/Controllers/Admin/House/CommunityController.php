<?php

namespace App\Http\Controllers\Admin\House;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Admin\System\CityController;
use App\Http\Controllers\Admin\System\DistrictController;
use App\Http\Controllers\Admin\System\ProvinceController;
use App\Rules\Admin\House\Community as RulesCommunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityController extends BaseController
{
    public $table_name = 'web_house_community';
    public $table_pk = 'community_id';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 首页
     */
    public function Index()
    {
        return view('Admin/House/Community/Index');
    }


    /**
     * 分页数据
     * @param $request
     */
    public function GetPage(Request $request)
    {
        $all = $request->all();
        $limit = isset($all['limit']) ? $all['limit'] : 10; //每页数量
        $param = isset($all['param']) ? $all['param'] : array(); //搜索参数

        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
        );

        if(count($param) > 0){
            //关键字
            if(isset($param['id']) && !empty($param['id'])){
                $where[] = [$this->table_name.'.'.$this->table_pk,'=',trim($param['id'])];
            }

            if(isset($param['community_name']) && !empty($param['community_name'])){
                $where[] = [$this->table_name.'.community_name','like','%'.trim($param['community_name']).'%'];
            }

            if(isset($param['property_company']) && !empty($param['property_company'])){
                $where[] = [$this->table_name.'.property_company','like','%'.trim($param['property_company']).'%'];
            }

            //更新日期
            if(isset($param['dateSelect']) && !empty($param['dateSelect'])){
                $dateSelect = explode(' ~ ',$param['dateSelect']);
                $begin_date = $dateSelect[0].' 00:00:00';
                $end_date = $dateSelect[1].' 23:59:59';
                $where[] = [$this->table_name.'.update_time','>=',$begin_date];
                $where[] = [$this->table_name.'.update_time','<=',$end_date];
            }
        }

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name','web_house_building_type.type_name as building_type_name','system_province.province_name','system_city.city_name','system_district.district_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('web_house_building_type',$this->table_name.'.building_type_id','=','web_house_building_type.type_id')
            ->leftJoin('system_province',$this->table_name.'.province_id','=','system_province.province_id')
            ->leftJoin('system_city',$this->table_name.'.city_id','=','system_city.city_id')
            ->leftJoin('system_district',$this->table_name.'.district_id','=','system_district.district_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $count = DB::table($this->table_name)
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('web_house_building_type',$this->table_name.'.building_type_id','=','web_house_building_type.type_id')
            ->leftJoin('system_province',$this->table_name.'.province_id','=','system_province.province_id')
            ->leftJoin('system_city',$this->table_name.'.city_id','=','system_city.city_id')
            ->leftJoin('system_district',$this->table_name.'.district_id','=','system_district.district_id')
            ->where($where)
            ->count();

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$count);
    }


    /**
     * 详情
     * @param int $id 记录id
     */
    public function GetInfo($id)
    {
        $where = array(
            [$this->table_name.'.'.$this->table_pk,'=',$id]
        );
        $info = DB::table($this->table_name)
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name','web_house_building_type.type_name as building_type_name','system_province.province_name','system_city.city_name','system_district.district_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
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
        elseif($info->is_delete == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'记录已被删除');
        }

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$info);
    }


    /**
     * 表单页
     * @param int $jump_type 类型，1-新增，2-修改
     * @param int $id 记录id
     */
    public function Form($jump_type,$id=0)
    {
        if($jump_type != 1 && $jump_type != 2)
        {
            return '跳转参数错误';
        }

        $type = new BuildingTypeController();
        $type_data = $type->GetList();

        $data = array();
        $data['jump_type'] = $jump_type;
        $data['type_data'] = $type_data['data'];

        if($jump_type == 2)
        {
            //详情
            $info_res = $this->GetInfo($id);
            if($info_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($info_res['code'],$info_res['msg']);
            }
            $info = $info_res['data'];
            $data['info'] = $info;
            $data['id'] = $info->id;

            //城市
            $city = new CityController();
            $city_res = $city->AccordingProvinceGetData($info->province_id);
            $data['city_data'] = $city_res['data'];

            //区域
            $district = new DistrictController();
            $district_res = $district->AccordingCityGetData($info->city_id);
            $data['district_data'] = $district_res['data'];
        }

        //省份
        $province = new ProvinceController();
        $province_data = $province->GetList();
        $data['province_data'] = $province_data['data'];

        return view('/Admin/House/Community/Form',$data);
    }


    /**
     * 新增
     *
     * @param $request
     *
     * @param $validator
     */
    public function Add(Request $request,RulesCommunity $validator)
    {
        $validator_res = $validator->Add($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $community_name = $all['community_name']; //小区名称
        $building_year = $all['building_year']; //建筑年代
        $building_type_id = $all['building_type_id']; //建筑类型
        $building_amount = $all['building_amount']; //楼栋总数
        $house_amount = $all['house_amount']; //房屋总数
        $property_company = $all['property_company']; //物业公司
        $property_fee = $all['property_fee']; //物业费
        $developers = $all['developers']; //开发商
        $province_id = $all['province_id']; //省份
        $city_id = $all['city_id']; //城市
        $district_id = $all['district_id']; //区域
        $detail_address = $all['detail_address']; //详细地址
        $second_hand_price = $all['second_hand_price']; //二手房价
        $is_show = $all['is_show']; //是否上架
        $is_top = $all['is_top']; //是否置顶
        $sort = $all['sort']; //排序号

        $province = new ProvinceController();
        $city = new CityController();
        $district = new DistrictController();

        $province_res = $province->GetInfo($province_id);
        if($province_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($province_res['code'],$province_res['msg']);
        }
        $province_info = $province_res['data'];

        $city_res = $city->GetInfo($city_id);
        if($city_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($city_res['code'],$city_res['msg']);
        }
        $city_info = $city_res['data'];

        $district_res = $district->GetInfo($district_id);
        if($district_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($district_res['code'],$district_res['msg']);
        }
        $district_info = $district_res['data'];

        $parsing_res = $this->common_class->AddressParsing($province_info->province_name,$city_info->city_name,$district_info->district_name,$detail_address);
        $parsing_res = json_decode($parsing_res,1);
        if($parsing_res['status'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat(1,$parsing_res['message']);
        }
        elseif($parsing_res['result']['reliability'] < 7)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'reliability='.$parsing_res['result']['reliability'].'，可信度太低，请输入更详细的地址');
        }
        elseif($parsing_res['result']['level'] < 9)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'level='.$parsing_res['result']['level'].'，精准度太低，请输入更详细的地址');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $insert_data = array(
                'community_name' => $community_name,
                'building_year' => $building_year,
                'building_type_id' => $building_type_id,
                'building_amount' => $building_amount,
                'house_amount' => $house_amount,
                'property_company' => $property_company,
                'property_fee' => $property_fee,
                'developers' => $developers,
                'province_id' => $province_id,
                'city_id' => $city_id,
                'district_id' => $district_id,
                'detail_address' => $detail_address,
                'latitude' => $parsing_res['result']['location']['lat'],
                'longitude' => $parsing_res['result']['location']['lng'],
                'second_hand_price' => $second_hand_price,
                'is_show' => $is_show,
                'is_top' => $is_top,
                'sort' => $sort,
                'create_time' => now(),
                'create_administrator_id' => $this->administrator_info['administrator_id'],
                'update_time' => now(),
                'update_administrator_id' => $this->administrator_info['administrator_id'],
            );
            $id = DB::table($this->table_name)
                ->insertGetId($insert_data);

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'操作失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'操作成功');
    }


    /**
     * 编辑/修改
     *
     * @param $request
     *
     * @param $validator
     */
    public function Edit(Request $request,RulesCommunity $validator)
    {
        $validator_res = $validator->Edit($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //id
        $community_name = $all['community_name']; //小区名称
        $building_year = $all['building_year']; //建筑年代
        $building_type_id = $all['building_type_id']; //建筑类型
        $building_amount = $all['building_amount']; //楼栋总数
        $house_amount = $all['house_amount']; //房屋总数
        $property_company = $all['property_company']; //物业公司
        $property_fee = $all['property_fee']; //物业费
        $developers = $all['developers']; //开发商
        $province_id = $all['province_id']; //省份
        $city_id = $all['city_id']; //城市
        $district_id = $all['district_id']; //区域
        $detail_address = $all['detail_address']; //详细地址
        $second_hand_price = $all['second_hand_price']; //二手房价
        $is_show = $all['is_show']; //是否上架
        $is_top = $all['is_top']; //是否置顶
        $sort = $all['sort']; //排序号

        $province = new ProvinceController();
        $city = new CityController();
        $district = new DistrictController();

        $province_res = $province->GetInfo($province_id);
        if($province_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($province_res['code'],$province_res['msg']);
        }
        $province_info = $province_res['data'];

        $city_res = $city->GetInfo($city_id);
        if($city_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($city_res['code'],$city_res['msg']);
        }
        $city_info = $city_res['data'];

        $district_res = $district->GetInfo($district_id);
        if($district_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($district_res['code'],$district_res['msg']);
        }
        $district_info = $district_res['data'];

        $parsing_res = $this->common_class->AddressParsing($province_info->province_name,$city_info->city_name,$district_info->district_name,$detail_address);
        $parsing_res = json_decode($parsing_res,1);
        if($parsing_res['status'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat(1,$parsing_res['message']);
        }
        elseif($parsing_res['result']['reliability'] < 7)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'reliability='.$parsing_res['result']['reliability'].'，可信度太低，请输入更详细的地址');
        }
        elseif($parsing_res['result']['level'] < 9)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'level='.$parsing_res['result']['level'].'，精准度太低，请输入更详细的地址');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $update_data = array(
                'community_name' => $community_name,
                'building_year' => $building_year,
                'building_type_id' => $building_type_id,
                'building_amount' => $building_amount,
                'house_amount' => $house_amount,
                'property_company' => $property_company,
                'property_fee' => $property_fee,
                'developers' => $developers,
                'province_id' => $province_id,
                'city_id' => $city_id,
                'district_id' => $district_id,
                'detail_address' => $detail_address,
                'latitude' => $parsing_res['result']['location']['lat'],
                'longitude' => $parsing_res['result']['location']['lng'],
                'second_hand_price' => $second_hand_price,
                'is_show' => $is_show,
                'is_top' => $is_top,
                'sort' => $sort,
                'update_time' => now(),
                'update_administrator_id' => $this->administrator_info['administrator_id'],
            );
            $where = array(
                [$this->table_pk,'=',$id]
            );

            DB::table($this->table_name)
                ->where($where)
                ->update($update_data);

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'操作失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'操作成功');
    }


    /**
     * 删除
     *
     * @param $request
     *
     * @param $validator
     */
    public function Delete(Request $request,RulesCommunity $validator)
    {
        $validator_res = $validator->Delete($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //id

        if(!is_array($id)){
            $id = explode(',',$id);
        }

        // 启动事务
        Db::beginTransaction();
        try {
            foreach($id as $k => $v){
                $delete_ps = '管理员id：'.$this->administrator_info['administrator_id'].' 手动删除';

                $update_data = array(
                    'is_delete' => 1,
                    'delete_time' => now(),
                    'delete_ps' => $delete_ps,
                    'delete_administrator_id' => $this->administrator_info['administrator_id'],
                );
                $where = array(
                    [$this->table_pk,'=',$v]
                );
                DB::table($this->table_name)
                    ->where($where)
                    ->update($update_data);
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'操作失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'操作成功');
    }


    /**
     * 选择页
     * @param int $is_radio 是否单选，1-是，2-否
     */
    public function Select($is_radio=1)
    {
        $data = array();
        $data['is_radio'] = $is_radio;

        return view('/Admin/House/Community/Select',$data);
    }
}
