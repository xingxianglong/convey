<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Common\User\UserController as CommonUser;
use App\Rules\Api\User\User as RulesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    public $table_name = 'web_user';
    public $table_pk = 'user_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 小程序授权
     * @param Request $request
     * @param RulesUser $validator
     */
    public function XcxAccredit(Request $request,RulesUser $validator)
    {
        $validator_res = $validator->XcxAccredit($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $js_code = $all['js_code']; //登陆时获取的code
        $user_name = $all['user_name']; //用户昵称
        $head = $all['head']; //用户头像

        $url="https://api.weixin.qq.com/sns/jscode2session?appid=$this->XCX_APPID&secret=$this->XCX_APPSECRET&js_code=$js_code&grant_type=authorization_code";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $curl_res = curl_exec($curl);
        curl_close($curl);

        if(!isset($curl_res) || empty($curl_res)) {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到用户标识');
        }

        $wx_arr = json_decode($curl_res,true);

        if(isset($wx_arr['errcode']) && $wx_arr['errcode'] != 0)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'errcode:'.$wx_arr['errcode'].'，errmsg:'.$wx_arr['errmsg']);
        }

        if(!isset($wx_arr['openid']) || empty($wx_arr['openid'])){
            return $this->common_class->ajaxDataReturnFormat(1,'微信没有返回openid');
        }

        $openid = $wx_arr['openid'];

        // 启动事务
        Db::beginTransaction();
        try {
            $common_user = new CommonUser();

            $where = array(
                ['is_delete','=',0],
                ['wx_openid','=',$openid],
            );
            $info = DB::table($this->table_name)
                ->where($where)
                ->first();
            if(empty($info))
            {
                $code = $common_user->GenerateCode();
                $insert_data = array(
                    'user_code' => $code,
                    'user_name' => $user_name,
                    'wx_openid' => $openid,
                    'head' => $head,
                    'create_time' => now(),
                    'update_time' => now(),
                );
                $id = DB::table($this->table_name)
                    ->insertGetId($insert_data);

                $where = array(
                    [$this->table_pk,'=',$id],
                );
                $info = DB::table($this->table_name)
                    ->where($where)
                    ->first();
            }
            else
            {
                $update_data = array(
                    'user_name' => $user_name,
                    'head' => $head,
                    'update_time' => now(),
                );
                $where = array(
                    [$this->table_pk,'=',$info->user_id]
                );
                DB::table($this->table_name)
                    ->where($where)
                    ->update($update_data);
            }

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'操作失败：'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        $info = (array)$info;
        $data = array(
            'token' => encrypt($info)
        );

        return $this->common_class->ajaxDataReturnFormat(0,'授权成功',$data);
    }


    /**
     * 小程序授权
     * @param Request $request
     */
    public function GetInfo(Request $request)
    {
        $where = array(
            [$this->table_name.'.'.$this->table_pk,'=',$request->user_info->user_id]
        );
        $info = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.user_code',$this->table_name.'.user_name',$this->table_name.'.head',$this->table_name.'.create_time')
            ->where($where)
            ->first();

        //关联顾问
        $info->store_id = 0;
        $info->consultant_id = 0;
        $consultant_res = $this->GetConsultant($info->id);
        if(isset($consultant_res['data']->consultant_id))
        {
            $info->store_id = $consultant_res['data']->store_id;
            $info->consultant_id = $consultant_res['data']->consultant_id;
        }

        return $this->common_class->ajaxDataReturnFormat(0,'成功',$info);
    }


    /**
     * 查询顾问
     * @param int $user_id 用户id
     */
    public function GetConsultant($user_id)
    {
        $where = array(
            ['user_id','=',$user_id],
            ['is_delete','=',0],
        );
        $info = DB::table('web_store_consultant')
            ->select('store_id','consultant_id')
            ->where($where)
            ->first();

        return $this->common_class->ajaxDataReturnFormat(0,'成功',$info);
    }
}
