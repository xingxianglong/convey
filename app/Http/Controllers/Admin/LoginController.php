<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Common\System\ConfigController as CommonConfigController;
use App\Rules\Admin\Login;
use App\Http\Controllers\Common\CommonController;

class LoginController extends Controller
{

    public $common_class = null;


    /**
     * 初始化
     */
    public function __construct()
    {
        $this->common_class = new CommonController();
    }


    /**
     * 登录页
     */
    public function Index()
    {
        $title = $this->title();

        $data = array();
        $data['title'] = $title;
        $data['login_account'] = isset($_COOKIE['login_account']) ? $_COOKIE['login_account'] : '';
        return view('Admin/Login/Index',$data);
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
     * 后台用户登录验证
     */
    public function LoginValidation(Request $request,Login $validator)
    {
        $validator_res = $validator->validator($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $login_account = $all['login_account']; //账号
        $login_password = $all['login_password']; //密码

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

        //查询用户
        $where = array(
            ['login_account','=',$login_account],
            ['login_password','=',$login_password],
        );
        $info = DB::table('system_administrator')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'账号或密码错误');
        }
        elseif($info->is_ban == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'管理员已被禁用');
        }
        elseif($info->is_delete == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'管理员已被删除');
        }
        $info = (array)$info;
        $info = encrypt($info);

        //是否记住账号
        $remember = isset($all['remember']) ? $all['remember'] : 0;
        if($remember == 1){
            setcookie("login_account",$login_account,time()+3600*12*720,"/");
        }else{
            setcookie("login_account",'',time()+3600*12*720,"/");
        }

        //用户数据保存
        setcookie('administrator_info',$info,time()+86400,"/");
        session(['administrator_info' => $info]);

        return $this->common_class->ajaxDataReturnFormat(0,'登录成功');
    }


    /**
     * 退出
     */
    public function Out()
    {
        session(null);
        setcookie("administrator_info","",time()-86400,"/");
        return $this->common_class->ajaxDataReturnFormat(1,'退出成功');
    }

}
