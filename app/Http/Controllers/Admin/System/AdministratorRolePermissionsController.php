<?php

namespace App\Http\Controllers\Admin\System;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\DB;

class AdministratorRolePermissionsController extends BaseController
{
    public $table_name = 'system_administrator_role_permissions';
    public $table_pk = 'permissions_id';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 映射角色菜单权限
     *
     * @param int $id 角色id
     *
     * @param array $two_menu_id 二级菜单栏目id
     *
     * @return array
     */
    public function MappingRoleMenuPermissions($id,$two_menu_id)
    {
        // 启动事务
        Db::beginTransaction();
        try {
            //删除此角色的所有映射
            $update_data = array(
                'is_delete' => 1,
                'delete_administrator_id' => $this->administrator_info['administrator_id'],
                'delete_time' => now(),
            );
            $where = array(
                'role_id' => $id
            );
            DB::table($this->table_name)
                ->where($where)
                ->update($update_data);

            //映射所选的菜单
            foreach($two_menu_id as $k => $v){
                $where = array(
                    'role_id' => $id,
                    'menu_id' => $v,
                );
                $info = DB::table($this->table_name)
                    ->where($where)
                    ->first();
                //找不到记录，则新增
                if(empty($info))
                {
                    $insert_data = array(
                        'role_id' => $id,
                        'menu_id' => $v,
                        'create_time' => now(),
                        'create_administrator_id' => $this->administrator_info['administrator_id'],
                        'update_time' => now(),
                        'update_administrator_id' => $this->administrator_info['administrator_id'],
                    );
                    DB::table($this->table_name)
                        ->insert($insert_data);
                }
                else
                    {
                    //有记录，更新
                    $update_data = array(
                        'is_delete' => 0,
                        'delete_administrator_id' => 0,
                        'delete_time' => null,
                        'update_time' => now(),
                        'update_administrator_id' => $this->administrator_info['administrator_id'],
                    );
                    $where = array(
                        'permissions_id' => $info->permissions_id
                    );
                    DB::table($this->table_name)
                        ->where($where)
                        ->update($update_data);
                }
            }

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'映射失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'映射成功');
    }


    /**
     * 根据角色获取数据
     *
     * @param int $role_id 角色id
     *
     * @return array
     */
    public function AccordingRoleGetData($role_id)
    {
        $where = array(
            ['role_id','=',$role_id],
            ['is_delete','=',0]
        );
        $data = DB::table($this->table_name)
            ->where($where)
            ->get();
        $menu_id_arr = array();
        foreach($data as $k => $v){
            $menu_id_arr[] = $v->menu_id;
        }

        return $menu_id_arr;
    }
}
