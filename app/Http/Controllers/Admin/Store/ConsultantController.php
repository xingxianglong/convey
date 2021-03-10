<?php

namespace App\Http\Controllers\Admin\Store;
use App\Http\Controllers\Admin\BaseController;
use App\Rules\Admin\Store\Consultant as RulesConsultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultantController extends BaseController
{
    public $table_name = 'web_store_consultant';
    public $table_pk = 'consultant_id';


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
        return view('Admin/Store/Consultant/Index');
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
        $store_id = isset($all['store_id']) ? $all['store_id'] : 0; //门店id

        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
        );

        if(!empty($store_id))
        {
            $where[] = [$this->table_name.'.store_id','=',$store_id];
        }

        if(count($param) > 0){
            //关键字
            if(isset($param['id']) && !empty($param['id'])){
                $where[] = [$this->table_name.'.'.$this->table_pk,'=',trim($param['id'])];
            }

            if(isset($param['store_name']) && !empty($param['store_name'])){
                $where[] = ['web_store.store_name','like','%'.trim($param['store_name']).'%'];
            }

            if(isset($param['consultant_name']) && !empty($param['consultant_name'])){
                $where[] = [$this->table_name.'.consultant_name','like','%'.trim($param['consultant_name']).'%'];
            }

            if(isset($param['phone']) && !empty($param['phone'])){
                $where[] = [$this->table_name.'.phone','=',trim($param['phone'])];
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name','web_store.store_name','web_store_consultant_position.position_name','web_user.user_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant_position',$this->table_name.'.position_id','=','web_store_consultant_position.position_id')
            ->leftJoin('web_user',$this->table_name.'.user_id','=','web_user.user_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $count = DB::table($this->table_name)
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant_position',$this->table_name.'.position_id','=','web_store_consultant_position.position_id')
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name','web_store.store_name','web_store.note','web_store_consultant_position.position_name','web_user.user_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('web_store',$this->table_name.'.store_id','=','web_store.store_id')
            ->leftJoin('web_store_consultant_position',$this->table_name.'.position_id','=','web_store_consultant_position.position_id')
            ->leftJoin('web_user',$this->table_name.'.user_id','=','web_user.user_id')
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

        $position = new ConsultantPositionController();
        $position_data = $position->GetList();

        $data = array();
        $data['jump_type'] = $jump_type;
        $data['position_data'] = $position_data['data'];

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

        return view('/Admin/Store/Consultant/Form',$data);
    }


    /**
     * 新增
     *
     * @param $request
     *
     * @param $validator
     */
    public function Add(Request $request,RulesConsultant $validator)
    {
        $validator_res = $validator->Add($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $store_id = $all['store_id']; //门店id
        $user_id = $all['user_id']; //用户id
        $consultant_name = $all['consultant_name']; //顾问姓名
        $position_id = $all['position_id']; //职位id
        $phone = $all['phone']; //手机号码
        $sex = $all['sex']; //性别
        $induction_date = $all['induction_date']; //入职日期
        $head = $all['head']; //头像
        $head_ext = isset($all['head_ext']) ? $all['head_ext'] : ''; //头像文件后缀名
        $head_size = isset($all['head_size']) ? $all['head_size'] : ''; //头像文件大小

        if($this->common_class->checkPhone($phone) == false)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'手机格式错误');
        }

        $binding_res = $this->CheckUserBinding($user_id);
        if($binding_res == true)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该用户已绑定');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $insert_data = array(
                'store_id' => $store_id,
                'user_id' => $user_id,
                'consultant_name' => $consultant_name,
                'position_id' => $position_id,
                'phone' => $phone,
                'sex' => $sex,
                'induction_date' => $induction_date,
                'head' => $head,
                'head_ext' => $head_ext,
                'head_size' => $head_size,
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
    public function Edit(Request $request,RulesConsultant $validator)
    {
        $validator_res = $validator->Edit($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //id
        $store_id = $all['store_id']; //门店id
        $user_id = $all['user_id']; //用户id
        $consultant_name = $all['consultant_name']; //顾问姓名
        $position_id = $all['position_id']; //职位id
        $phone = $all['phone']; //手机号码
        $sex = $all['sex']; //性别
        $induction_date = $all['induction_date']; //入职日期
        $head = $all['head']; //头像
        $head_ext = isset($all['head_ext']) ? $all['head_ext'] : ''; //头像文件后缀名
        $head_size = isset($all['head_size']) ? $all['head_size'] : ''; //头像文件大小

        if($this->common_class->checkPhone($phone) == false)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'手机格式错误');
        }

        $binding_res = $this->CheckUserBinding($user_id,$id);
        if($binding_res == true)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该用户已绑定');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $update_data = array(
                'store_id' => $store_id,
                'user_id' => $user_id,
                'consultant_name' => $consultant_name,
                'position_id' => $position_id,
                'phone' => $phone,
                'sex' => $sex,
                'induction_date' => $induction_date,
                'head' => $head,
                'head_ext' => $head_ext,
                'head_size' => $head_size,
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
    public function Delete(Request $request,RulesConsultant $validator)
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
     * 检验用户绑定
     * @param int $user_id 用户id
     * @param int $id 记录id
     */
    public function CheckUserBinding($user_id,$id=0)
    {
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.user_id','=',$user_id],
        );
        if(!empty($id))
        {
            $where[] = [$this->table_name.'.'.$this->table_pk,'<>',$id];
        }
        $info = DB::table($this->table_name)
            ->where($where)
            ->first();
        if(empty($info))
        {
            return false;
        }

        return true;
    }


    /**
     * 选择页
     * @param int $is_radio 是否单选，1-是，2-否
     * @param int $store_id 门店id
     */
    public function Select($is_radio=1,$store_id=0)
    {
        $data = array();
        $data['is_radio'] = $is_radio;
        $data['store_id'] = $store_id;

        return view('/Admin/Store/Consultant/Select',$data);
    }
}
