<?php

namespace App\Http\Controllers\Admin\System;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Common\System\ConfigController as CommonConfigController;
use App\Rules\Admin\System\Administrator as RulesAdministrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministratorController extends BaseController
{
    public $table_name = 'system_administrator';
    public $table_pk = 'administrator_id';


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
        return view('Admin/System/Administrator/Index');
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
            [$this->table_name.'.is_super_admin','<>',1],
        );

        if(count($param) > 0){
            //关键字
            if(isset($param['id']) && !empty($param['id'])){
                $where[] = [$this->table_name.'.'.$this->table_pk,'=',trim($param['id'])];
            }

            if(isset($param['administrator_name']) && !empty($param['administrator_name'])){
                $where[] = [$this->table_name.'.administrator_name','like','%'.trim($param['administrator_name']).'%'];
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name','system_administrator_role.role_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('system_administrator_role',$this->table_name.'.role_id','=','system_administrator_role.role_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $count = DB::table($this->table_name)
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('system_administrator_role',$this->table_name.'.role_id','=','system_administrator_role.role_id')
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name','system_administrator_role.role_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->leftJoin('system_administrator_role',$this->table_name.'.role_id','=','system_administrator_role.role_id')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到记录');
        }
        elseif($info->is_ban == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'管理员已被禁用');
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

        $role = new AdministratorRoleController();
        $role_data = $role->GetList();

        $data = array();
        $data['jump_type'] = $jump_type;
        $data['role_data'] = $role_data['data'];

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

        return view('/Admin/System/Administrator/Form',$data);
    }


    /**
     * 新增
     *
     * @param $request
     *
     * @param $validator
     */
    public function Add(Request $request,RulesAdministrator $validator)
    {
        $validator_res = $validator->Add($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $administrator_name = $all['administrator_name']; //管理员名称
        $login_account = $all['login_account']; //登陆账号
        $login_password = $all['login_password']; //登录密码
        $confirm_password = $all['confirm_password']; //确认密码
        $role_id = $all['role_id']; //角色id
        $phone = $all['phone']; //手机号码
        $email = $all['email']; //电子邮箱
        $birthday = isset($all['birthday']) ? $all['birthday'] : ''; //出生日期
        $sex = $all['sex']; //性别
        $head = isset($all['head']) ? $all['head'] : ''; //头像
        $head_ext = isset($all['head_ext']) ? $all['head_ext'] : ''; //头像文件后缀名
        $head_size = isset($all['head_size']) ? $all['head_size'] : ''; //头像文件大小

        if($login_password != $confirm_password)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'两次密码不统一');
        }

        if($this->common_class->checkPhone($phone) == false)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'手机格式错误');
        }

        if($this->common_class->checkEmail($email) == false)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'邮箱格式错误');
        }

        //验证密码格式
        $check_password = $this->common_class->passwordFormatCheck($login_password);
        if($check_password['code'] == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,$check_password['msg']);
        }

        //查询信息
        $where = array(
            ['login_account','=',$login_account],
            ['is_delete','=',0],
        );
        $info = DB::table($this->table_name)
            ->where($where)
            ->first();
        if(!empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'登陆账号已存在，请勿重复添加');
        }

        //获取后台登录加密格式
        $key = 'SYSTEMLOGINPASSWORDENCRYPTIONFORMAT';
        $config = new CommonConfigController();
        $config_res = $config->AccordingKeyGetValue($key);
        if($config_res['code'] == 1){
            return $this->common_class->ajaxDataReturnFormat(1,$config_res['msg']);
        }
        $passowrd_format = json_decode($config_res['data']['_value'],true);
        //密码加密
        $login_password = call_user_func($passowrd_format[0],call_user_func($passowrd_format[1],$login_password.$passowrd_format[2]));

        // 启动事务
        Db::beginTransaction();
        try {
            $insert_data = array(
                'administrator_name' => $administrator_name,
                'login_account' => $login_account,
                'login_password' => $login_password,
                'role_id' => $role_id,
                'phone' => $phone,
                'email' => $email,
                'birthday' => $birthday ? $birthday : null,
                'sex' => $sex,
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
    public function Edit(Request $request,RulesAdministrator $validator)
    {
        $validator_res = $validator->Edit($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //id
        $administrator_name = $all['administrator_name']; //管理员名称
        $role_id = $all['role_id']; //角色id
        $phone = $all['phone']; //手机号码
        $email = $all['email']; //电子邮箱
        $birthday = isset($all['birthday']) ? $all['birthday'] : ''; //出生日期
        $sex = $all['sex']; //性别
        $head = isset($all['head']) ? $all['head'] : ''; //头像
        $head_ext = isset($all['head_ext']) ? $all['head_ext'] : ''; //头像文件后缀名
        $head_size = isset($all['head_size']) ? $all['head_size'] : ''; //头像文件大小

        if($this->common_class->checkPhone($phone) == false)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'手机格式错误');
        }

        if($this->common_class->checkEmail($email) == false)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'邮箱格式错误');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $update_data = array(
                'administrator_name' => $administrator_name,
                'role_id' => $role_id,
                'phone' => $phone,
                'email' => $email,
                'birthday' => $birthday ? $birthday : null,
                'sex' => $sex,
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
     * 表单页 重置登录账号密码
     * @param int $id 记录id
     */
    public function FormAccount($id)
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

        return view('/Admin/System/Administrator/FormAccount',$data);
    }


    /**
     * 修改登录账号
     *
     * @param $request
     *
     * @param $validator
     */
    public function EditAccount(Request $request,RulesAdministrator $validator)
    {
        $validator_res = $validator->EditAccount($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //id
        $login_account = $all['login_account']; //登陆账号
        $login_password = $all['login_password']; //登录密码

        //验证密码格式
        $check_password = $this->common_class->passwordFormatCheck($login_password);
        if($check_password['code'] == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,$check_password['msg']);
        }

        //查询信息
        $where = array(
            ['login_account','=',$login_account],
            [$this->table_pk,'<>',$id],
            ['is_delete','=',0],
        );
        $info = DB::table($this->table_name)
            ->where($where)
            ->first();
        if(!empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'登陆账号已存在，请勿重复添加');
        }

        //获取后台登录加密格式
        $key = 'SYSTEMLOGINPASSWORDENCRYPTIONFORMAT';
        $config = new CommonConfigController();
        $config_res = $config->AccordingKeyGetValue($key);
        if($config_res['code'] == 1){
            return $this->common_class->ajaxDataReturnFormat(1,$config_res['msg']);
        }
        $passowrd_format = json_decode($config_res['data']['_value'],true);
        //密码加密
        $login_password = call_user_func($passowrd_format[0],call_user_func($passowrd_format[1],$login_password.$passowrd_format[2]));


        // 启动事务
        Db::beginTransaction();
        try {
            $update_data = array(
                'login_account' => $login_account,
                'login_password' => $login_password,
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
    public function Delete(Request $request,RulesAdministrator $validator)
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
