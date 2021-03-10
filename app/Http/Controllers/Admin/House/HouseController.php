<?php

namespace App\Http\Controllers\Admin\House;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Common\House\HouseController as CommonHouse;
use App\Rules\Admin\House\House as RulesHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HouseController extends BaseController
{
    public $table_name = 'web_house';
    public $table_pk = 'house_id';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 首页
     * @param int $is_deal 成交动态房源，1-是，2-否
     */
    public function Index($is_deal)
    {
        if($is_deal != 1 && $is_deal != 2)
        {
            return '成交动态参数错误';
        }

        $data = array();
        $data['is_deal'] = $is_deal;

        return view('Admin/House/House/Index',$data);
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
        $is_deal = isset($all['is_deal']) ? $all['is_deal'] : 2; //成交动态，1-是，2-否

        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.is_deal','=',$is_deal],
        );

        if(count($param) > 0){
            //关键字
            if(isset($param['id']) && !empty($param['id'])){
                $where[] = [$this->table_name.'.'.$this->table_pk,'=',trim($param['id'])];
            }

            if(isset($param['store_name']) && !empty($param['store_name'])){
                $where[] = ['web_store.store_name','like','%'.trim($param['store_name']).'%'];
            }

            if(isset($param['title']) && !empty($param['title'])){
                $where[] = [$this->table_name.'.title','like','%'.trim($param['title']).'%'];
            }

            if(isset($param['house_code']) && !empty($param['house_code'])){
                $where[] = [$this->table_name.'.house_code','=',trim($param['house_code'])];
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name','web_store.store_name','web_store.note as store_note','web_store_consultant.consultant_name','web_house_classify.classify_name','web_house_community.community_name','web_house_direction.direction_name','web_house_decorate.decorate_name','web_house_payment_way.way_name','web_house_brand_apartment.apartment_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant',$this->table_name.'.consultant_id','=','web_store_consultant.consultant_id')
            ->leftJoin('web_house_classify',$this->table_name.'.classify_id','=','web_house_classify.classify_id')
            ->leftJoin('web_house_community',$this->table_name.'.community_id','=','web_house_community.community_id')
            ->leftJoin('web_house_direction',$this->table_name.'.direction_id','=','web_house_direction.direction_id')
            ->leftJoin('web_house_decorate',$this->table_name.'.decorate_id','=','web_house_decorate.decorate_id')
            ->leftJoin('web_house_payment_way',$this->table_name.'.payment_way_id','=','web_house_payment_way.way_id')
            ->leftJoin('web_house_brand_apartment',$this->table_name.'.brand_apartment_id','=','web_house_brand_apartment.apartment_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $count = DB::table($this->table_name)
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant',$this->table_name.'.consultant_id','=','web_store_consultant.consultant_id')
            ->leftJoin('web_house_classify',$this->table_name.'.classify_id','=','web_house_classify.classify_id')
            ->leftJoin('web_house_community',$this->table_name.'.community_id','=','web_house_community.community_id')
            ->leftJoin('web_house_direction',$this->table_name.'.direction_id','=','web_house_direction.direction_id')
            ->leftJoin('web_house_decorate',$this->table_name.'.decorate_id','=','web_house_decorate.decorate_id')
            ->leftJoin('web_house_payment_way',$this->table_name.'.payment_way_id','=','web_house_payment_way.way_id')
            ->leftJoin('web_house_brand_apartment',$this->table_name.'.brand_apartment_id','=','web_house_brand_apartment.apartment_id')
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name','web_store.store_name','web_store.note as store_note','web_store_consultant.consultant_name','web_house_classify.classify_name','web_house_community.community_name','web_house_direction.direction_name','web_house_decorate.decorate_name','web_house_payment_way.way_name','web_house_brand_apartment.apartment_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant',$this->table_name.'.consultant_id','=','web_store_consultant.consultant_id')
            ->leftJoin('web_house_classify',$this->table_name.'.classify_id','=','web_house_classify.classify_id')
            ->leftJoin('web_house_community',$this->table_name.'.community_id','=','web_house_community.community_id')
            ->leftJoin('web_house_direction',$this->table_name.'.direction_id','=','web_house_direction.direction_id')
            ->leftJoin('web_house_decorate',$this->table_name.'.decorate_id','=','web_house_decorate.decorate_id')
            ->leftJoin('web_house_payment_way',$this->table_name.'.payment_way_id','=','web_house_payment_way.way_name')
            ->leftJoin('web_house_brand_apartment',$this->table_name.'.brand_apartment_id','=','web_house_brand_apartment.apartment_id')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到记录');
        }
        elseif($info->is_delete == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'管理员已被删除');
        }

        //轮播图
        $banner = new BannerController();
        $banner_res = $banner->AccordingHouseGetData($info->id);
        if($banner_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($banner_res['code'],$banner_res['msg']);
        }
        $info->banner_data = $banner_res['data'];

        //标签
        $label_mapping = new LabelMappingController();
        $label_mapping_res = $label_mapping->AccordingHouseGetData($info->id);
        if($label_mapping_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($label_mapping_res['code'],$label_mapping_res['msg']);
        }
        $label_arr = array();
        foreach($label_mapping_res['data'] as $k => $v)
        {
            $label_arr[] = $v->label_id;
        }
        $info->label_data = $label_arr;

        //配置
        $configuration_mapping = new ConfigurationMappingController();
        $configuration_mapping_res = $configuration_mapping->AccordingHouseGetData($info->id);
        if($configuration_mapping_res['code'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat($configuration_mapping_res['code'],$configuration_mapping_res['msg']);
        }
        $configuration_arr = array();
        foreach($configuration_mapping_res['data'] as $k => $v)
        {
            $configuration_arr[] = $v->configuration_id;
        }
        $info->configuration_data = $configuration_arr;

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$info);
    }


    /**
     * 表单页
     * @param int $jump_type 类型，1-新增，2-修改
     * @param int $id 记录id
     * @param int $is_deal 成交动态
     */
    public function Form($jump_type,$id=0,$is_deal)
    {
        if($jump_type != 1 && $jump_type != 2)
        {
            return '跳转参数错误';
        }

        $data = array();
        $data['jump_type'] = $jump_type;
        $data['is_deal'] = $is_deal;

        $brand_apartment = new BrandApartmentController();
        $brand_apartment_data = $brand_apartment->GetList();
        $data['brand_apartment_data'] = $brand_apartment_data['data'];

        $classify = new ClassifyController();
        $classify_data = $classify->GetList();
        $data['classify_data'] = $classify_data['data'];

        $direction = new DirectionController();
        $direction_data = $direction->GetList();
        $data['direction_data'] = $direction_data['data'];

        $decorate = new DecorateController();
        $decorate_data = $decorate->GetList();
        $data['decorate_data'] = $decorate_data['data'];

        $payment_way = new PaymentWayController();
        $payment_way_data = $payment_way->GetList();
        $data['payment_way_data'] = $payment_way_data['data'];

        $label = new LabelController();
        $label_data = $label->GetList();
        $data['label_data'] = $label_data['data'];

        $configuration = new ConfigurationController();
        $configuration_data = $configuration->GetList();
        $data['configuration_data'] = $configuration_data['data'];

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
        }

        return view('/Admin/House/House/Form',$data);
    }


    /**
     * 新增
     *
     * @param $request
     *
     * @param $validator
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
        $classify_id = $all['classify_id']; //分类id
        $direction_id = $all['direction_id']; //朝向id
        $decorate_id = $all['decorate_id']; //装修id
        $payment_way_id = $all['payment_way_id']; //付款方式id
        $brand_apartment_id = $all['brand_apartment_id']; //品牌公寓id
        $title = $all['title']; //标题
        $room = $all['room']; //室
        $hall = $all['hall']; //厅
        $toilet = $all['toilet']; //卫
        $balcony = $all['balcony']; //阳台
        $kitchen = $all['kitchen']; //厨房
        $rent = $all['rent']; //租金
        $acreage = $all['acreage']; //面积
        $total_floor = $all['total_floor']; //总楼层
        $floor = $all['floor']; //楼层
        $is_key = $all['is_key']; //钥匙
        $is_elevator = $all['is_elevator']; //电梯
        $entire_or_joint = $all['entire_or_joint']; //整租合租
        $is_optimization = $all['is_optimization']; //优选好房
        $is_show = $all['is_show']; //是否上架
        $is_top = $all['is_top']; //是否置顶
        $sort = $all['sort']; //排序号
        $cover = $all['cover']; //封面图
        $cover_ext = isset($all['cover_ext']) ? $all['cover_ext'] : ''; //封面图文件后缀名
        $cover_size = isset($all['cover_size']) ? $all['cover_size'] : ''; //封面图文件大小
        $banner_img = $all['banner_img'] ? $all['banner_img'] : array(); //轮播图
        $banner_img_ext = isset($all['banner_img_ext']) ? $all['banner_img_ext'] : array(); //轮播图文件后缀名
        $banner_img_size = isset($all['banner_img_size']) ? $all['banner_img_size'] : array(); //轮播图文件大小
        $label_id = $all['label_id']; //标签
        $configuration_id = $all['configuration_id']; //配置
        $is_deal = $all['is_deal']; //成交动态房源
        $trading_time = isset($all['trading_time']) ? $all['trading_time'] : ''; //成交动态房源

        if($is_deal == 1)
        {
            if(empty($trading_time))
            {
                return $this->common_class->ajaxDataReturnFormat(1,'请选择成交日期');
            }
        }

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
                'is_show' => $is_show,
                'is_top' => $is_top,
                'sort' => $sort,
                'cover' => $cover,
                'cover_ext' => $cover_ext,
                'cover_size' => $cover_size,
                'trading_time' => $trading_time ? $trading_time : null,
                'is_deal' => $is_deal,
                'create_time' => now(),
                'create_administrator_id' => $this->administrator_info['administrator_id'],
                'update_time' => now(),
                'update_administrator_id' => $this->administrator_info['administrator_id'],
            );
            $id = DB::table($this->table_name)
                ->insertGetId($insert_data);

            $banner = new BannerController();
            $banner_res = $banner->AccordingHouseUpdate($id,$banner_img,$banner_img_ext,$banner_img_size);
            if($banner_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($banner_res['code'],$banner_res['msg']);
            }

            $label_mapping = new LabelMappingController();
            $label_mapping_res = $label_mapping->AccordingHouseUpdateLabel($id,$label_id);
            if($label_mapping_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($label_mapping_res['code'],$label_mapping_res['msg']);
            }

            $configuration_mapping = new ConfigurationMappingController();
            $configuration_mapping_res = $configuration_mapping->AccordingHouseUpdateConfiguration($id,$configuration_id);
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

        return $this->common_class->ajaxDataReturnFormat(0,'操作成功');
    }


    /**
     * 编辑/修改
     *
     * @param $request
     *
     * @param $validator
     */
    public function Edit(Request $request,RulesHouse $validator)
    {
        $validator_res = $validator->Edit($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //id
        $store_id = $all['store_id']; //门店id
        $consultant_id = $all['consultant_id']; //顾问id
        $community_id = $all['community_id']; //小区id
        $classify_id = $all['classify_id']; //分类id
        $direction_id = $all['direction_id']; //朝向id
        $decorate_id = $all['decorate_id']; //装修id
        $payment_way_id = $all['payment_way_id']; //付款方式id
        $brand_apartment_id = $all['brand_apartment_id']; //品牌公寓id
        $title = $all['title']; //标题
        $room = $all['room']; //室
        $hall = $all['hall']; //厅
        $toilet = $all['toilet']; //卫
        $balcony = $all['balcony']; //阳台
        $kitchen = $all['kitchen']; //厨房
        $rent = $all['rent']; //租金
        $acreage = $all['acreage']; //面积
        $total_floor = $all['total_floor']; //总楼层
        $floor = $all['floor']; //楼层
        $is_key = $all['is_key']; //钥匙
        $is_elevator = $all['is_elevator']; //电梯
        $entire_or_joint = $all['entire_or_joint']; //整租合租
        $is_optimization = $all['is_optimization']; //优选好房
        $is_show = $all['is_show']; //是否上架
        $is_top = $all['is_top']; //是否置顶
        $sort = $all['sort']; //排序号
        $cover = $all['cover']; //封面图
        $cover_ext = isset($all['cover_ext']) ? $all['cover_ext'] : ''; //封面图文件后缀名
        $cover_size = isset($all['cover_size']) ? $all['cover_size'] : ''; //封面图文件大小
        $banner_img = $all['banner_img'] ? $all['banner_img'] : array(); //轮播图
        $banner_img_ext = isset($all['banner_img_ext']) ? $all['banner_img_ext'] : array(); //轮播图文件后缀名
        $banner_img_size = isset($all['banner_img_size']) ? $all['banner_img_size'] : array(); //轮播图文件大小
        $label_id = $all['label_id']; //标签
        $configuration_id = $all['configuration_id']; //配置
        $is_deal = $all['is_deal']; //成交动态房源
        $trading_time = isset($all['trading_time']) ? $all['trading_time'] : ''; //成交动态房源

        if($is_deal == 1)
        {
            if(empty($trading_time))
            {
                return $this->common_class->ajaxDataReturnFormat(1,'请选择成交日期');
            }
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $update_data = array(
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
                'is_show' => $is_show,
                'is_top' => $is_top,
                'sort' => $sort,
                'cover' => $cover,
                'cover_ext' => $cover_ext,
                'cover_size' => $cover_size,
                'trading_time' => $trading_time ? $trading_time : null,
                'update_time' => now(),
                'update_administrator_id' => $this->administrator_info['administrator_id'],
            );
            $where = array(
                [$this->table_pk,'=',$id]
            );

            DB::table($this->table_name)
                ->where($where)
                ->update($update_data);

            $banner = new BannerController();
            $banner_res = $banner->AccordingHouseUpdate($id,$banner_img,$banner_img_ext,$banner_img_size);
            if($banner_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($banner_res['code'],$banner_res['msg']);
            }

            $label_mapping = new LabelMappingController();
            $label_mapping_res = $label_mapping->AccordingHouseUpdateLabel($id,$label_id);
            if($label_mapping_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($label_mapping_res['code'],$label_mapping_res['msg']);
            }

            $configuration_mapping = new ConfigurationMappingController();
            $configuration_mapping_res = $configuration_mapping->AccordingHouseUpdateConfiguration($id,$configuration_id);
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

        return $this->common_class->ajaxDataReturnFormat(0,'操作成功');
    }


    /**
     * 删除
     *
     * @param $request
     *
     * @param $validator
     */
    public function Delete(Request $request,RulesHouse $validator)
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
}
