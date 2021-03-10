<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Common\House\HouseController as CommonHouse;
use App\Http\Controllers\Common\System\CityController;
use App\Http\Controllers\Common\System\DistrictController;
use App\Http\Controllers\Common\System\ProvinceController;
use App\Rules\Api\House\House as RulesHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HouseController extends BaseController
{
    public $table_name = 'web_house';
    public $table_pk = 'house_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 分页列表
     * @param Request $request
     * @param RulesHouse $validator
     */
    public function GetPage(Request $request,RulesHouse $validator)
    {
        $validator_res = $validator->GetPage($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();
        $headers = $request->server->getHeaders();

        $limit = isset($all['limit']) ? $all['limit'] : 10; //每页数量
        $is_brand = isset($all['is_brand']) ? $all['is_brand'] : 0; //是否品牌公寓
        $brand_apartment_id = isset($all['brand_apartment_id']) ? $all['brand_apartment_id'] : 0; //品牌公寓
        $decorate_id = isset($all['decorate_id']) ? $all['decorate_id'] : 0; //装修
        $direction_id = isset($all['direction_id']) ? $all['direction_id'] : 0; //朝向
        $label_id = isset($all['label_id']) ? $all['label_id'] : array(); //标签
        $payment_way_id = isset($all['payment_way_id']) ? $all['payment_way_id'] : 0; //付款方式
        $min_acreage = isset($all['min_acreage']) ? $all['min_acreage'] : 0; //最小面积
        $max_acreage = isset($all['max_acreage']) ? $all['max_acreage'] : 0; //最大面积
        $entire_or_joint = isset($all['entire_or_joint']) ? $all['entire_or_joint'] : 0; //整租合租，1-整租，2-合租
        $min_rent = isset($all['min_rent']) ? $all['min_rent'] : 0; //最小租金
        $max_rent = isset($all['max_rent']) ? $all['max_rent'] : 0; //最大租金
        $is_elevator = isset($all['is_elevator']) ? $all['is_elevator'] : 0; //电梯，1-有，2-没有
        $is_key = isset($all['is_key']) ? $all['is_key'] : 0; //钥匙，1-有，2-无
        $keyword = isset($all['keyword']) ? $all['keyword'] : ''; //关键字
        $sort = isset($all['sort']) ? $all['sort'] : 0; //排序方式，0-最新发布，1-价格从高到低，2-价格从低到高
        $province_name = isset($all['province_name']) ? $all['province_name'] : ''; //省份
        $city_name = isset($all['city_name']) ? $all['city_name'] : ''; //城市
        $district_name = isset($all['district_name']) ? $all['district_name'] : ''; //区域
        $consultant_id = isset($all['consultant_id']) ? $all['consultant_id'] : 0; //顾问id


        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.is_show','=',1],
            [$this->table_name.'.is_deal','=',2],
        );

        if($is_brand == 1){
            $where[] = [$this->table_name.'.brand_apartment_id','<>',0];
        }

        if(!empty($brand_apartment_id)){
            $where[] = [$this->table_name.'.brand_apartment_id','=',$brand_apartment_id];
        }

        if(!empty($decorate_id)){
            $where[] = [$this->table_name.'.decorate_id','=',$decorate_id];
        }

        if(!empty($direction_id)){
            $where[] = [$this->table_name.'.direction_id','=',$direction_id];
        }

        $house_id_arr = array();
        $label_mapping = new LabelMappingController($request);
        if(is_array($label_id) && count($label_id) > 0)
        {
            $label_mapping_res = $label_mapping->AccordingArrayLabelGetHouse($label_id);
            if($label_mapping_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($label_mapping_res['code'],$label_mapping_res['msg']);
            }
            $label_mapping_data = $label_mapping_res['data'];
            foreach($label_mapping_data as $k => $v)
            {
                $house_id_arr[] = $v->house_id;
            }
        }

        if(!empty($payment_way_id)){
            $where[] = [$this->table_name.'.payment_way_id','=',$payment_way_id];
        }

        if(!empty($entire_or_joint)){
            $where[] = [$this->table_name.'.entire_or_joint','=',$entire_or_joint];
        }

        if(!empty($is_elevator)){
            $where[] = [$this->table_name.'.is_elevator','=',$is_elevator];
        }

        if(!empty($is_key)){
            $where[] = [$this->table_name.'.is_key','=',$is_key];
        }

        if(!empty($min_rent) && !empty($max_rent))
        {
            $where[] = [$this->table_name.'.rent','>=',$min_rent];
            $where[] = [$this->table_name.'.rent','<=',$max_rent];
        }

        if(!empty($min_acreage) && !empty($max_acreage))
        {
            $where[] = [$this->table_name.'.acreage','>=',$min_acreage];
            $where[] = [$this->table_name.'.acreage','<=',$max_acreage];
        }

        $province_info = null;
        if(!empty($province_name))
        {
            $province = new ProvinceController();
            $province_res = $province->AccordingNameGetInfo($province_name);
            if($province_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($province_res['code'],$province_res['msg']);
            }
            $province_info = $province_res['data'];
        }

        $city_info = null;
        if(!empty($city_name))
        {
            if(empty($province_info))
            {
                return $this->common_class->ajaxDataReturnFormat(1,'请先选择省份');
            }
            $city = new CityController();
            $city_res = $city->AccordingProvinceIdNameGetInfo($province_info->id,$city_name);
            if($city_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($city_res['code'],$city_res['msg']);
            }
            $city_info = $city_res['data'];
        }

        $district_info = null;
        if(!empty($district_name))
        {
            if(empty($city_info))
            {
                return $this->common_class->ajaxDataReturnFormat(1,'请先选择城市');
            }
            $district = new DistrictController();
            $district_res = $district->AccordingCityIdNameGetInfo($city_info->id,$district_name);
            if($district_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($district_res['code'],$district_res['msg']);
            }
            $district_info = $district_res['data'];
        }

        if(!empty($consultant_id))
        {
            if(!isset($headers['TOKEN']) || empty($headers['TOKEN'])){
                return $this->common_class->ajaxDataReturnFormat(2,'请先登录');
            }

            try {
                $user_info = decrypt($headers['TOKEN']);
            } catch (\Exception $e) {
                return $this->common_class->ajaxDataReturnFormat(1,$e->getMessage());
            }

            //查询用户
            $tmp_where = array(
                ['user_id','=',$user_info['user_id']],
            );
            $user_info = DB::table('web_user')
                ->where($tmp_where)
                ->first();
            if(empty($user_info))
            {
                echo json_encode($this->common_class->ajaxDataReturnFormat(1,'token错误'));
                die;
            }
            elseif($user_info->is_ban == 1)
            {
                echo json_encode($this->common_class->ajaxDataReturnFormat(1,'用户已被禁用'));
                die;
            }
            elseif($user_info->is_delete == 1)
            {
                echo json_encode($this->common_class->ajaxDataReturnFormat(1,'用户已被删除'));
                die;
            }
        }

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.title',$this->table_name.'.cover',$this->table_name.'.is_optimization',$this->table_name.'.rent',$this->table_name.'.acreage',$this->table_name.'.room',$this->table_name.'.hall',$this->table_name.'.toilet',$this->table_name.'.entire_or_joint')
            ->leftJoin('web_house_community',$this->table_name.'.community_id','=','web_house_community.community_id')
            ->where($where)
            ->when($house_id_arr,function($query,$house_id_arr){
                return $query->whereIn($this->table_name.'.'.$this->table_pk,$house_id_arr);
            })
            ->when($province_info,function($query,$province_info){
                return $query->where('web_house_community.province_id','=',$province_info->id);
            })
            ->when($city_info,function($query,$city_info){
                return $query->where('web_house_community.city_id','=',$city_info->id);
            })
            ->when($district_info,function($query,$district_info){
                return $query->where('web_house_community.district_id','=',$district_info->id);
            })
            ->when($consultant_id,function($query,$consultant_id){
                return $query->where($this->table_name.'.consultant_id','=',$consultant_id);
            })
            ->where(function($query) use($keyword){
                $query->when($keyword,function($query,$keyword){
                    return $query->orWhere($this->table_name.'.title','like','%'.$keyword.'%')
                        ->orWhere('web_house_community.community_name','like','%'.$keyword.'%');
                });
            })
            ->when($sort,function($query,$sort){
                if($sort == 2)
                {
                    return $query->orderBy($this->table_name.'.rent','desc');
                }
                elseif($sort == 3)
                {
                    return $query->orderBy($this->table_name.'.rent','asc');
                }
            })
            ->orderBy($this->table_name.'.is_top','asc')
            ->orderBy($this->table_name.'.sort','asc')
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        foreach($data as $k => $v)
        {
            //标签
            $lavel_res = $label_mapping->AccordingHouseGetData($v->id,2);
            $data[$k]->label_data = $lavel_res['data'];
        }

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$data->total(),$data->lastPage());
    }


    /**
     * 详情
     * @param Request $request
     * @param RulesHouse $validator
     */
    public function GetInfo(Request $request,RulesHouse $validator)
    {
        $validator_res = $validator->GetInfo($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();
        $headers = $request->server->getHeaders();

        if(isset($headers['TOKEN']) || !empty($headers['TOKEN'])){
            try {
                $user_info = decrypt($headers['TOKEN']);
            } catch (\Exception $e) {
                return $this->common_class->ajaxDataReturnFormat(1,$e->getMessage());
            }

            //查询用户
            $where = array(
                ['user_id','=',$user_info['user_id']],
            );
            $user_info = DB::table('web_user')
                ->where($where)
                ->first();
            if(empty($user_info))
            {
                echo json_encode($this->common_class->ajaxDataReturnFormat(1,'token错误'));
                die;
            }
            elseif($user_info->is_ban == 1)
            {
                echo json_encode($this->common_class->ajaxDataReturnFormat(1,'用户已被禁用'));
                die;
            }
            elseif($user_info->is_delete == 1)
            {
                echo json_encode($this->common_class->ajaxDataReturnFormat(1,'用户已被删除'));
                die;
            }
        }


        $id = $all['id']; //记录id

        $where = array(
            [$this->table_name.'.'.$this->table_pk,'=',$id]
        );
        $info = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.house_code',$this->table_name.'.title',$this->table_name.'.room',$this->table_name.'.hall',$this->table_name.'.toilet',$this->table_name.'.balcony',$this->table_name.'.kitchen',$this->table_name.'.rent',$this->table_name.'.acreage',$this->table_name.'.total_floor',$this->table_name.'.floor',$this->table_name.'.is_elevator',$this->table_name.'.entire_or_joint',$this->table_name.'.is_key',$this->table_name.'.is_optimization',$this->table_name.'.is_show',$this->table_name.'.is_delete',$this->table_name.'.pv',$this->table_name.'.update_time','web_house_classify.classify_name','web_store.store_name',$this->table_name.'.consultant_id','web_store_consultant.consultant_name','web_store_consultant.phone as consultant_phone','web_store_consultant.head as consultant_head',$this->table_name.'.community_id','web_house_community.community_name','web_house_community.latitude','web_house_community.longitude','web_house_direction.direction_name','web_house_decorate.decorate_name','web_house_payment_way.way_name','web_house_brand_apartment.apartment_name')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant',$this->table_name.'.consultant_id','=','web_store_consultant.consultant_id')
            ->leftJoin('web_house_community',$this->table_name.'.community_id','=','web_house_community.community_id')
            ->leftJoin('web_house_classify',$this->table_name.'.classify_id','=','web_house_classify.classify_id')
            ->leftJoin('web_house_direction',$this->table_name.'.direction_id','=','web_house_direction.direction_id')
            ->leftJoin('web_house_decorate',$this->table_name.'.decorate_id','=','web_house_decorate.decorate_id')
            ->leftJoin('web_house_payment_way',$this->table_name.'.payment_way_id','=','web_house_payment_way.way_id')
            ->leftJoin('web_house_brand_apartment',$this->table_name.'.brand_apartment_id','=','web_house_brand_apartment.apartment_id')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到记录');
        }
        elseif($info->is_show != 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该房源已下架');
        }
        elseif($info->is_delete != 0)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该房源已删除');
        }
        unset($info->is_show);
        unset($info->is_delete);

        $info->update_time = $info->update_time ? date('Y-m-d',strtotime($info->update_time)) : '';
        $info->apartment_name = $info->apartment_name ? $info->apartment_name : '';

        //浏览量
        $pv_res = $this->PvIncrement($info->id);
        if($pv_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($pv_res['code'],$pv_res['msg']);
        }
        $info->pv = $info->pv + 1;

        //轮播图
        $banner = new BannerController($request);
        $banner_res = $banner->AccordingHouseGetData($info->id);
        $info->banner_data = $banner_res['data'];

        //标签
        $label_mapping = new LabelMappingController($request);
        $lavel_res = $label_mapping->AccordingHouseGetData($info->id);
        $info->label_data = $lavel_res['data'];

        //配置
        $configuration_mapping = new ConfigurationMappingController($request);
        $configuration_res = $configuration_mapping->AccordingHouseGetData($request,$info->id);
        $info->configuration_data = $configuration_res['data'];

        //同小区成交
        $same_res = $this->GetSameCommunityHouseData($info->id,$info->community_id);
        $info->same_data = $same_res['data'];

        //收藏
        $is_collect = 0;
        if(isset($user_info))
        {
            $info->is_collect = $this->IsCollect($user_info->user_id,$id);
        }
        else
        {
            $info->is_collect = 0;
        }


        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$info);
    }


    /**
     * 浏览增量
     * @param int $id 记录id
     */
    public function PvIncrement($id)
    {
        // 启动事务
        Db::beginTransaction();
        try {
            $where = array(
                [$this->table_pk,'=',$id]
            );
            DB::table($this->table_name)
                ->where($where)
                ->increment('pv');

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'pv增量失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'pv增量成功');
    }


    /**
     * 同小区成交数据
     * @param int $id 房源id
     * @param int $community_id 小区id
     */
    public function GetSameCommunityHouseData($id,$community_id)
    {
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.is_show','=',1],
            [$this->table_name.'.is_deal','=',1],
            [$this->table_name.'.community_id','=',$community_id],
            [$this->table_name.'.'.$this->table_pk,'<>',$id],
        );
        $data = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.entire_or_joint',$this->table_name.'.room',$this->table_name.'.hall',$this->table_name.'.toilet',$this->table_name.'.acreage',$this->table_name.'.rent',$this->table_name.'.trading_time','web_store.store_name','web_store_consultant.consultant_name','web_house_direction.direction_name')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant',$this->table_name.'.consultant_id','=','web_store_consultant.consultant_id')
            ->leftJoin('web_house_direction',$this->table_name.'.direction_id','=','web_house_direction.direction_id')
            ->where($where)
            ->orderBy($this->table_name.'.is_top','asc')
            ->orderBy($this->table_name.'.sort','asc')
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data);
    }


    /**
     * 是否收藏
     * @param int $user_id 用户id
     * @param int $id 记录id
     */
    public function IsCollect($user_id,$id)
    {
        $where = array(
            ['is_delete','=',0],
            ['user_id','=',$user_id],
            ['house_id','=',$id],
        );
        $count = DB::table('web_user_collect')
            ->where($where)
            ->count();

        return $count;
    }


    /**
     * 发布
     * @param Request $request
     * @param RulesHouse $validator
     */
    public function Add(Request $request,RulesHouse $validator)
    {
        $validator_res = $validator->Add($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $store_id = $all['store_id']; //门店id
        $consultant_id = $all['consultant_id']; //顾问id
        $community_id = $all['community_id']; //小区id
        $classify_id = 0; //分类id
        $direction_id = $all['direction_id']; //朝向id
        $decorate_id = $all['decorate_id']; //装修id
        $payment_way_id = $all['payment_way_id']; //付款方式id
        $brand_apartment_id = 0; //品牌公寓id
        $title = $all['title']; //标题
        $room = 0; //室
        $hall = 0; //厅
        $toilet = 0; //卫
        $balcony = 0; //阳台
        $kitchen = 0; //厨房
        $rent = $all['rent']; //租金
        $acreage = $all['acreage']; //面积
        $total_floor = 0; //总楼层
        $floor = $all['floor']; //楼层
        $is_key = 2; //钥匙
        $is_elevator = $all['is_elevator']; //电梯
        $entire_or_joint = 0; //整租合租
        $is_optimization = 2; //优选好房
        $banner_img = $all['banner_img'] ? $all['banner_img'] : array(); //轮播图
        $label_id = $all['label_id']; //标签
        $configuration_id = $all['configuration_id']; //配置


        // 启动事务
        Db::beginTransaction();
        try {
            $common_house = new CommonHouse();
            $code = $common_house->GenerateCode();

            $insert_data = array(
                'house_code' => $code,
                'store_id' => $store_id,
                'consultant_id' => $consultant_id,
                'community_id' => $community_id,
                'classify_id' => $classify_id,
                'direction_id' => $direction_id,
                'decorate_id' => $decorate_id,
                'payment_way_id' => $payment_way_id,
                'brand_apartment_id' => $brand_apartment_id,
                'title' => $title,
                'room' => $room,
                'hall' => $hall,
                'toilet' => $toilet,
                'balcony' => $balcony,
                'kitchen' => $kitchen,
                'rent' => $rent,
                'acreage' => $acreage,
                'total_floor' => $total_floor,
                'floor' => $floor,
                'is_key' => $is_key,
                'is_elevator' => $is_elevator,
                'entire_or_joint' => $entire_or_joint,
                'is_optimization' => $is_optimization,
                'cover' => $banner_img[0],
                'create_time' => now(),
                'create_administrator_id' => $request->user_info->user_id,
                'update_time' => now(),
                'update_administrator_id' => $request->user_info->user_id,
            );
            $id = DB::table($this->table_name)
                ->insertGetId($insert_data);

            $banner = new BannerController($request);
            $banner_res = $banner->AccordingHouseUpdate($id,$banner_img,$request->user_info->user_id);
            if($banner_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($banner_res['code'],$banner_res['msg']);
            }

            $label_mapping = new LabelMappingController($request);
            $label_mapping_res = $label_mapping->AccordingHouseUpdateLabel($id,$label_id,$request->user_info->user_id);
            if($label_mapping_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($label_mapping_res['code'],$label_mapping_res['msg']);
            }

            $configuration_mapping = new ConfigurationMappingController($request);
            $configuration_mapping_res = $configuration_mapping->AccordingHouseUpdateConfiguration($id,$configuration_id,$request->user_info->user_id);
            if($configuration_mapping_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($configuration_mapping_res['code'],$configuration_mapping_res['msg']);
            }

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'操作失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'操作成功，请等待审核');
    }
}
