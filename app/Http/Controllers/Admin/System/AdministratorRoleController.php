<?php

namespace App\Http\Controllers\Admin\System;
use App\Http\Controllers\Admin\BaseController;
use App\Rules\Admin\System\AdministratorRole as RulesAdministratorRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministratorRoleController extends BaseController
{
    public $table_name = 'system_administrator_role';
    public $table_pk = 'role_id';


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
        return view('Admin/System/AdministratorRole/Index');
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

            if(isset($param['role_name']) && !empty($param['role_name'])){
                $where[] = [$this->table_name.'.role_name','like','%'.trim($param['role_name']).'%'];
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $count = DB::table($this->table_name)
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
            ->where($where)
            ->count();

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$count);
    }


    /**
     * 列表数据
     *
     * @return array
     */
    public function GetList(){
        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
        );

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id')
            ->where($where)
            ->orderby($this->table_name.'.'.$this->table_pk,'asc')
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data);
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
            ->select($this->table_name.'.*',$this->table_name.'.'.$this->table_pk.' as id','update_administrator.administrator_name as update_administrator_name')
            ->leftJoin('system_administrator as update_administrator',$this->table_name.'.update_administrator_id','=','update_administrator.administrator_id')
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

        //查询角色映射权限
        $permissions = new AdministratorRolePermissionsController();
        $permissions_res = $permissions->AccordingRoleGetData($info->id);
        $info->menu_id = $permissions_res;

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

        $menu_class = new MenuController();
        $menu_data = $menu_class->GetMenuData();

        $data = array();
        $data['jump_type'] = $jump_type;
        $data['menu_data'] = $menu_data['data'];

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

        return view('/Admin/System/AdministratorRole/Form',$data);
    }


    /**
     * 新增
     *
     * @param $request
     *
     * @param $validator
     */
    public function Add(Request $request,RulesAdministratorRole $validator)
    {
        $validator_res = $validator->Add($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $role_name = $all['role_name']; //角色名称
        $two_menu_id = isset($all['two_menu_id']) ? $all['two_menu_id'] : array(); //二级菜单id

        if(!is_array($two_menu_id))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'二级菜单id必须为数组');
        }

        //查询信息
        $where = array(
            ['role_name','=',$role_name],
            ['is_delete','=',0],
        );
        $info = DB::table($this->table_name)
            ->where($where)
            ->first();
        if(!empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'角色已存在，请勿重复添加');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $insert_data = array(
                'role_name' => $role_name,
                'create_time' => now(),
                'create_administrator_id' => $this->administrator_info['administrator_id'],
                'update_time' => now(),
                'update_administrator_id' => $this->administrator_info['administrator_id'],
            );
            $id = DB::table($this->table_name)
                ->insertGetId($insert_data);

            //映射角色菜单权限
            $permissions = new AdministratorRolePermissionsController();
            $permissions_res = $permissions->MappingRoleMenuPermissions($id,$two_menu_id);
            if($permissions_res['code'] == 1){
                return $this->common_class->ajaxDataReturnFormat($permissions_res['code'],$permissions_res['msg']);
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
    public function Edit(Request $request,RulesAdministratorRole $validator)
    {
        $validator_res = $validator->Edit($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $id = $all['id']; //id
        $role_name = $all['role_name']; //角色名称
        $two_menu_id = isset($all['two_menu_id']) ? $all['two_menu_id'] : array(); //二级菜单id

        if(!is_array($two_menu_id))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'二级菜单id必须为数组');
        }

        //查询信息
        $where = array(
            ['role_name','=',$role_name],
            ['is_delete','=',0],
            [$this->table_pk,'<>',$id]
        );
        $info = DB::table($this->table_name)
            ->where($where)
            ->first();
        if(!empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'角色已存在，请勿重复添加');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $update_data = array(
                'role_name' => $role_name,
                'update_time' => now(),
                'update_administrator_id' => $this->administrator_info['administrator_id'],
            );
            $where = array(
                [$this->table_pk,'=',$id]
            );

            DB::table($this->table_name)
                ->where($where)
                ->update($update_data);

            //映射角色菜单权限
            $permissions = new AdministratorRolePermissionsController();
            $permissions_res = $permissions->MappingRoleMenuPermissions($id,$two_menu_id);
            if($permissions_res['code'] == 1){
                return $this->common_class->ajaxDataReturnFormat($permissions_res['code'],$permissions_res['msg']);
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
    public function Delete(Request $request,RulesAdministratorRole $validator)
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
