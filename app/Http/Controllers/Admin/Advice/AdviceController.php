<?php

namespace App\Http\Controllers\Admin\Advice;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdviceController extends BaseController
{
    public $table_name = 'web_advice';
    public $table_pk = 'advice_id';


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
        return view('Admin/Advice/Advice/Index');
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

            if(isset($param['is_read']) && !empty($param['is_read'])){
                $where[] = [$this->table_name.'.is_read','=',trim($param['is_read'])];
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','web_user.user_name')
            ->leftJoin('web_user',$this->table_name.'.user_id','=','web_user.user_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $count = DB::table($this->table_name)
            ->leftJoin('web_user',$this->table_name.'.user_id','=','web_user.user_id')
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','web_user.user_name')
            ->leftJoin('web_user',$this->table_name.'.user_id','=','web_user.user_id')
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

        $info->img = explode(',',$info->img);

        $data['info'] = $info;
        $data['id'] = $info->id;

        if($info->is_read != 1)
        {
            $read_res = $this->UploadRead($info->id);
            if($read_res['code'] != 0)
            {
                return $this->common_class->ajaxDataReturnFormat($read_res['code'],$read_res['msg']);
            }
            $info->is_read = 1;
            $info->read_time = now();
        }

        return view('/Admin/Advice/Advice/Detail',$data);
    }


    /**
     * 修改阅读
     * @param int $id 记录id
     */
    public function UploadRead($id)
    {
        // 启动事务
        Db::beginTransaction();
        try {
            $update_data = array(
                'is_read' => 1,
                'read_time' => now(),
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
            return $this->common_class->ajaxDataReturnFormat(1,'修改阅读失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'修改阅读成功');
    }

}
