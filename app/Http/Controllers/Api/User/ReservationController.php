<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Rules\Api\User\Reservation as RulesReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends BaseController
{
    public $table_name = 'web_user_reservation';
    public $table_pk = 'reservation_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 新增预约
     * @param Request $request
     * @param RulesReservation $validator
     */
    public function Add(Request $request,RulesReservation $validator)
    {
        $validator_res = $validator->Add($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $house_id = $all['house_id']; //房源id
        $nickname = $all['nickname']; //用户姓名
        $phone = $all['phone']; //手机号码
        $look_house_begin_time = $all['look_house_begin_time']; //看房开始时间
        $look_house_ent_time = $all['look_house_ent_time']; //看房结束时间
        $note = isset($all['note']) ? $all['note'] : ''; //备注

        //查询房源
        $where = array(
            ['house_id','=',$house_id]
        );
        $house_info = DB::table('web_house')
            ->select('house_id as id','is_show','is_delete')
            ->where($where)
            ->first();
        if(empty($house_info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到该房源');
        }
        elseif($house_info->is_show != 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该房源已下架');
        }
        elseif($house_info->is_delete == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该房源已删除');
        }

        if($this->common_class->checkPhone($phone) == false)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'手机格式错误');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $where = array(
                [$this->table_name.'.user_id','=',$request->user_info->user_id],
                [$this->table_name.'.house_id','=',$house_id],
                [$this->table_name.'.is_delete','=',0],
            );
            $info = DB::table($this->table_name)
                ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.look_house_begin_time',$this->table_name.'.look_house_ent_time')
                ->where($where)
                ->orderBy($this->table_name.'.look_house_ent_time','desc')
                ->first();
            if(!empty($info))
            {
                if(strtotime($info->look_house_ent_time) >= time())
                {
                    return $this->common_class->ajaxDataReturnFormat(1,'您已有预约');
                }
            }

            $insert_data = array(
                'user_id' => $request->user_info->user_id,
                'house_id' => $house_id,
                'nickname' => $nickname,
                'phone' => $phone,
                'look_house_begin_time' => $look_house_begin_time,
                'look_house_ent_time' => $look_house_ent_time,
                'note' => $note,
                'create_time' => now(),
                'create_user_id' => $request->user_info->user_id,
                'update_time' => now(),
                'update_user_id' => $request->user_info->user_id,
            );
            DB::table($this->table_name)
                ->insert($insert_data);

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'预约失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'预约成功');
    }


    /**
     * 分页列表
     * @param Request $request
     * @param RulesReservation $validator
     */
    public function GetPage(Request $request,RulesReservation $validator)
    {
        $validator_res = $validator->GetPage($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $limit = isset($all['limit']) ? $all['limit'] : 10; //每页数量

        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.user_id','=',$request->user_info->user_id],
        );

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.house_id','web_house.title','web_house.cover','web_house.rent','web_house.acreage','web_house.room','web_house.hall','web_house.toilet','web_house.entire_or_joint','web_store_consultant.consultant_name','web_store_consultant.head as consultant_head','web_store_consultant.phone as consultant_phone')
            ->leftJoin('web_house',$this->table_name.'.house_id','=','web_house.house_id')
            ->leftJoin('web_store_consultant','web_house.consultant_id','=','web_store_consultant.consultant_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);


        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$data->total(),$data->lastPage());
    }


    /**
     * 预约分页列表
     * @param Request $request
     * @param RulesReservation $validator
     */
    public function GetConsultantPage(Request $request,RulesReservation $validator)
    {
        $validator_res = $validator->GetConsultantPage($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $consultant_id = $all['consultant_id']; //顾问id
        $limit = isset($all['limit']) ? $all['limit'] : 10; //每页数量

        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            ['web_house.consultant_id','=',$consultant_id],
        );

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.house_id',$this->table_name.'.nickname',$this->table_name.'.phone',$this->table_name.'.look_house_begin_time',$this->table_name.'.look_house_ent_time',$this->table_name.'.note','web_house.title','web_house.cover','web_house.rent','web_house.acreage','web_house.room','web_house.hall','web_house.toilet','web_house.entire_or_joint')
            ->leftJoin('web_house',$this->table_name.'.house_id','=','web_house.house_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$data->total(),$data->lastPage());
    }

}
