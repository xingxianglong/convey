<?php

namespace App\Http\Controllers\Admin\User;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    public $table_name = 'web_user';
    public $table_pk = 'user_id';


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
        return view('Admin/User/User/Index');
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

            if(isset($param['user_code']) && !empty($param['user_code'])){
                $where[] = [$this->table_name.'.user_code','=',trim($param['user_code'])];
            }

            if(isset($param['user_name']) && !empty($param['user_name'])){
                $where[] = [$this->table_name.'.user_name','like','%'.trim($param['user_name'].'%')];
            }

            //更新日期
            if(isset($param['dateSelect']) && !empty($param['dateSelect'])){
                $dateSelect = explode(' ~ ',$param['dateSelect']);
                $begin_date = $dateSelect[0].' 00:00:00';
                $end_date = $dateSelect[1].' 23:59:59';
                $where[] = [$this->table_name.'.create_time','>=',$begin_date];
                $where[] = [$this->table_name.'.create_time','<=',$end_date];
            }
        }

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $count = DB::table($this->table_name)
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id')
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

        return view('/Admin/User/User/Detail',$data);
    }


    /**
     * 选择页
     * @param int $is_radio 是否单选，1-是，2-否
     */
    public function Select($is_radio=1)
    {
        $data = array();
        $data['is_radio'] = $is_radio;

        return view('/Admin/User/User/Select',$data);
    }
}
