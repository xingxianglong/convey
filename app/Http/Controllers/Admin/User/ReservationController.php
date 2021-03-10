<?php

namespace App\Http\Controllers\Admin\User;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends BaseController
{
    public $table_name = 'web_user_reservation';
    public $table_pk = 'reservation_id';


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
        return view('Admin/User/Reservation/Index');
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

            if(isset($param['phone']) && !empty($param['phone'])){
                $where[] = [$this->table_name.'.phone','=',trim($param['phone'])];
            }

            if(isset($param['nickname']) && !empty($param['nickname'])){
                $where[] = [$this->table_name.'.nickname','=','%'.trim($param['nickname']).'%'];
            }

            //更新日期
            if(isset($param['dateSelect']) && !empty($param['dateSelect'])){
                $dateSelect = explode(' ~ ',$param['dateSelect']);
                $begin_date = $dateSelect[0].' 00:00:00';
                $end_date = $dateSelect[1].' 23:59:59';
                $where[] = [$this->table_name.'.look_house_begin_time','>=',$begin_date];
                $where[] = [$this->table_name.'.look_house_ent_time','<=',$end_date];
            }
        }

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','web_house.title as house_title')
            ->leftJoin('web_house',$this->table_name.'.house_id','=','web_house.house_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $count = DB::table($this->table_name)
            ->leftJoin('web_house',$this->table_name.'.house_id','=','web_house.house_id')
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','web_house.title as house_title')
            ->leftJoin('web_house',$this->table_name.'.house_id','=','web_house.house_id')
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
     * 详情页
     * @param int $id 记录id
     */
    public function Detail($id)
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

        return view('/Admin/User/Reservation/Detail',$data);
    }

}
