<?php

namespace App\Http\Controllers\Admin\System;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\DB;

class MenuController extends BaseController
{
    public $table_name = 'system_menu';
    public $table_pk = 'menu_id';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 获取一级菜单栏目数据
     *
     * @param int $administrator_info 管理员信息
     *
     * @return array
     */
    public function GetOneMenuData($administrator_info)
    {
        if($administrator_info['is_super_admin'] == 1){
            $where = array(
                ['is_delete','=',0],
                ['type_id','=',1],
                ['menu_name','<>',''],
                ['level','=',1],
            );
            $data = DB::table($this->table_name)
                ->select('*',$this->table_pk.' as id')
                ->where($where)
                ->orderBy('sort','asc')
                ->orderBy($this->table_pk,'asc')
                ->get();

            foreach($data as $k => $v){
                //查询二级菜单
                $where = array(
                    ['is_delete','=',0],
                    ['type_id','=',1],
                    ['menu_name','<>',''],
                    ['level','=',2],
                    ['parent_id','=',$v->menu_id],
                );
                $three_data = DB::table($this->table_name)
                    ->select('*',$this->table_pk.' as id')
                    ->where($where)
                    ->orderBy('sort','asc')
                    ->orderBy($this->table_pk,'asc')
                    ->get();
                $data[$k]->two_data = $three_data;
            }
        }
        else
        {
            //查询映射权限
            $where = array(
                ['is_delete','=',0],
                ['role_id','=',$administrator_info['role_id']],
            );
            $permissions_data = DB::table('system_administrator_role_permissions')
                ->where($where)
                ->get();
            $menu_id_arr = array();
            foreach($permissions_data as $k => $v){
                $menu_id_arr[] = $v->menu_id;
            }

            //查询二级菜单
            $where = array(
                ['is_delete','=',0],
                ['type_id','=',1],
                ['menu_name','<>',''],
                ['level','=',2],
            );
            $two_data = DB::table($this->table_name)
                ->where($where)
                ->whereIn('menu_id',$menu_id_arr)
                ->get();
            $one_menu_id_arr = array();
            foreach($two_data as $k => $v){
                $one_menu_id_arr[] = $v->parent_id;
            }

            //查询一级菜单
            $where = array(
                ['is_delete','=',0],
                ['type_id','=',1],
                ['menu_name','<>',''],
                ['level','=',1],
            );
            $data = DB::table($this->table_name)
                ->select('*',$this->table_pk.' as id')
                ->where($where)
                ->whereIn('menu_id',$one_menu_id_arr)
                ->orderBy('sort','asc')
                ->orderBy($this->table_pk,'asc')
                ->get();
            foreach($data as $k => $v){
                //查询二级菜单
                $where = array(
                    ['is_delete','=',0],
                    ['type_id','=',1],
                    ['menu_name','<>',''],
                    ['level','=',2],
                    ['parent_id','=',$v->menu_id],
                );
                $three_data = DB::table($this->table_name)
                    ->select('*',$this->table_pk.' as id')
                    ->where($where)
                    ->whereIn('menu_id',$menu_id_arr)
                    ->orderBy('sort','asc')
                    ->orderBy($this->table_pk,'asc')
                    ->get();
                $data[$k]->two_data = $three_data;
            }
        }

        if(count($data) <= 0){
            return $this->common_class->ajaxDataReturnFormat(1,'没有菜单数据');
        }

        return $this->common_class->ajaxDataReturnFormat(0,'菜单获取成功',$data);
    }


    /**
     * 获取二级菜单栏目数据
     *
     * @param int $administrator_info 管理员信息
     *
     * @param int $parent_id 父级id
     *
     * @return array
     */
    public function GetTwoMenuData($administrator_info,$parent_id)
    {
        //超级管理员查看所有
        if($administrator_info['is_super_admin'] == 1){
            $where = array(
                ['is_delete','=',0],
                ['type_id','=',1],
                ['menu_name','<>',''],
                ['level','=',2],
                ['parent_id','=',$parent_id],
            );
            $data = DB::table($this->table_name)
                ->select('*',$this->table_pk.' as id')
                ->where($where)
                ->orderBy('sort','asc')
                ->orderBy($this->table_pk,'asc')
                ->get();
            foreach($data as $k => $v){
                //查询二级菜单
                $where = array(
                    ['is_delete','=',0],
                    ['type_id','=',1],
                    ['menu_name','<>',''],
                    ['level','=',2],
                    ['parent_id','=',$v->menu_id],
                );
                $three_data = DB::table($this->table_name)
                    ->select('*',$this->table_pk.' as id')
                    ->where($where)
                    ->orderBy('sort','asc')
                    ->orderBy($this->table_pk,'asc')
                    ->get();
                $data[$k]->two_data = $three_data;
            }
        }
        else
            {
            //查询映射权限
            $where = array(
                ['is_delete','=',0],
                ['role_id','=',$administrator_info['role_id']],
            );
            $permissions_data = DB::table('system_administrator_role_permissions')
                ->where($where)->select();
            $menu_id_arr = array();
            foreach($permissions_data as $k => $v){
                $menu_id_arr[] = $v->menu_id;
            }

            //查询二级菜单
            $where = array(
                ['is_delete','=',0],
                ['type_id','=',1],
                ['menu_name','<>',''],
                ['level','=',2],
                ['menu_id','in',$menu_id_arr],
            );
            $two_data = DB::table($this->table_name)
                ->where($where)
                ->get();
            $one_menu_id_arr = array();
            foreach($two_data as $k => $v){
                $one_menu_id_arr[] = $v->parent_id;
            }

            //查询一级菜单
            $where = array(
                ['is_delete','=',0],
                ['type_id','=',1],
                ['menu_name','<>',''],
                ['level','=',1],
            );
            $data = DB::table($this->table_name)
                ->select('*',$this->table_pk.' as id')
                ->where($where)
                ->whereIn('menu_id',$one_menu_id_arr)
                ->orderBy('sort','asc')
                ->orderBy($this->table_pk,'asc')
                ->get();

            foreach($data as $k => $v){
                //查询二级菜单
                $where = array(
                    ['is_delete','=',0],
                    ['type_id','=',1],
                    ['menu_name','<>',''],
                    ['level','=',2],
                    ['parent_id','=',$v->menu_id],
                );
                $three_data = DB::table($this->table_name)
                    ->select('*',$this->table_pk.' as id')
                    ->where($where)
                    ->whereIn('menu_id',$menu_id_arr)
                    ->orderBy('sort','asc')
                    ->orderBy($this->table_pk,'asc')
                    ->get();
                $data[$k]->two_data = $three_data;
            }
        }

        if(count($data) <= 0){
            return $this->common_class->ajaxDataReturnFormat(1,'没有菜单数据');
        }

        return $this->common_class->ajaxDataReturnFormat(0,'菜单获取成功',$data);
    }


    /**
     * 获取菜单栏 全部等级
     *
     * @return array
     */
    public function GetMenuData(){
        $where = array(
            ['is_delete','=',0],
            ['type_id','=',1],
            ['menu_name','<>',''],
            ['level','=',1],
        );
        $data = DB::table($this->table_name)
            ->select('*',$this->table_pk.' as id')
            ->where($where)
            ->orderBy('sort','asc')
            ->orderBy($this->table_pk,'asc')
            ->get();

        foreach($data as $k => $v){
            //查询二级
            $where = array(
                ['is_delete','=',0],
                ['type_id','=',1],
                ['menu_name','<>',''],
                ['level','=',2],
                ['parent_id','=',$v->menu_id],
            );
            $two_data = DB::table($this->table_name)
                ->select('*',$this->table_pk.' as id')
                ->where($where)
                ->orderBy('sort','asc')
                ->orderBy($this->table_pk,'asc')
                ->get();

            foreach($two_data as $k2 => $v2){
                //查询三级菜单
                $where = array(
                    ['is_delete','=',0],
                    ['type_id','=',1],
                    ['menu_name','<>',''],
                    ['level','=',3],
                    ['parent_id','=',$v2->menu_id],
                );
                $three_data = DB::table($this->table_name)
                    ->select('*',$this->table_pk.' as id')
                    ->where($where)
                    ->orderBy('sort','asc')
                    ->orderBy($this->table_pk,'asc')
                    ->select();

                $two_data[$k2]->three_data = $three_data;
            }
            $data[$k]->two_data = $two_data;
        }

        if(count($data) <= 0){
            return $this->common_class->ajaxDataReturnFormat(1,'没有菜单数据');
        }

        return $this->common_class->ajaxDataReturnFormat(0,'',$data);
    }
}
