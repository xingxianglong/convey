<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\System\MenuController;
use App\Http\Controllers\Common\System\ConfigController as CommonConfigController;

class IndexController extends BaseController
{


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
        $data = array();

        $title = $this->title();
        $data['title'] = $title;

        //一级菜单栏目数据
        $one_menu_data = $this->GetOneMenu();
        if($one_menu_data['code'] == 1){
            return $this->common_class->ajaxDataReturnFormat($one_menu_data['code'],$one_menu_data['msg']);
        }
        $data['one_menu_data'] = $one_menu_data['data'];

        //二级菜单栏目数据
//        $menu_id = $one_menu_data['data'][0]->id;
//        $two_menu_data = $this->GetTwoMenu($menu_id);
//        if($two_menu_data['code'] == 1){
//            return $this->common_class->ajaxDataReturnFormat($two_menu_data['code'],$two_menu_data['msg']);
//        }
//        $data['two_menu_data'] = $two_menu_data['data'];

        $data['administrator_info'] = $this->administrator_info;

        return view('Admin/Index/Index',$data);
    }


    /**
     * 内容页
     */
    public function Main()
    {
        return view('Admin/Index/Main');
    }


    /**
     * 后台系统标题
     *
     * @return string
     */
    public function Title()
    {
        $key = 'SYSTEMMANAGEMENTTITLE';
        $config = new CommonConfigController();
        $res = $config->AccordingKeyGetValue($key);

        return $res['data']['_value'];
    }


    /**
     * 获取一级菜单栏目
     */
    public function GetOneMenu(){
        $menu_class = new MenuController();
        return $menu_class->GetOneMenuData($this->administrator_info);
    }


    /**
     * 获取二级菜单栏目
     *
     * @param int $menu_id 菜单id
     */
    public function GetTwoMenu($menu_id)
    {
        $parent_id = $menu_id ? $menu_id : '';
        if(empty($parent_id)){
            return $this->common_class->ajaxDataReturnFormat(1,'请选择一级菜单');
        }

        $menu_class = new MenuController();
        return $menu_class->GetTwoMenuData($this->administrator_info,$parent_id);
    }
}
