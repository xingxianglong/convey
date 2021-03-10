<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Common\CommonController;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->common_class = new CommonController();

        $headers = $request->server->getHeaders();

        if(!isset($headers['TOKEN']) || empty($headers['TOKEN'])){
            echo json_encode($this->common_class->ajaxDataReturnFormat(2,'请先登陆'));
            die;
        }

        try {
            $user_info = decrypt($headers['TOKEN']);
        } catch (\Exception $e) {
            echo json_encode($this->common_class->ajaxDataReturnFormat(1,$e->getMessage()));
            die;
        }

        //查询用户
        $where = array(
            ['user_id','=',$user_info['user_id']],
        );
        $info = DB::table('web_user')
            ->where($where)
            ->first();
        if(empty($info))
        {
            echo json_encode($this->common_class->ajaxDataReturnFormat(1,'token错误'));
            die;
        }
        elseif($info->is_ban == 1)
        {
            echo json_encode($this->common_class->ajaxDataReturnFormat(1,'用户已被禁用'));
            die;
        }
        elseif($info->is_delete == 1)
        {
            echo json_encode($this->common_class->ajaxDataReturnFormat(1,'用户已被删除'));
            die;
        }

        $request->user_info = $info;

        return $next($request);
    }
}
