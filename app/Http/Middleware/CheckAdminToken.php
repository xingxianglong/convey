<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Common\CommonController;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;

class CheckAdminToken
{

    public $common_class = null;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(isset($_COOKIE['administrator_info'])){
            try {
                $administrator_info = decrypt($_COOKIE['administrator_info']);
            } catch (\Exception $e) {
                return redirect('/Admin/bWQ1X0xvZ2luL0luZGV4X21kNQ==');
            }
        }
        if(!isset($administrator_info) || empty($administrator_info)){
            return redirect('/Admin/bWQ1X0xvZ2luL0luZGV4X21kNQ==');
        }

        $this->common_class = new CommonController();

        //查询用户
        $where = array(
            ['administrator_id','=',$administrator_info['administrator_id']],
        );
        $info = DB::table('system_administrator')
            ->where($where)
            ->first();
        if(empty($info))
        {
            return redirect('/Admin/bWQ1X0xvZ2luL0luZGV4X21kNQ==');
        }
        elseif($info->is_ban == 1)
        {
            return redirect('/Admin/bWQ1X0xvZ2luL0luZGV4X21kNQ==');
        }
        elseif($info->is_delete == 1)
        {
            return redirect('/Admin/bWQ1X0xvZ2luL0luZGV4X21kNQ==');
        }

        return $next($request);
    }
}
