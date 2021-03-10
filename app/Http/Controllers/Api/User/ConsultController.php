<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Rules\Api\User\Consult as RulesConsult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultController extends BaseController
{
    public $table_name = 'web_user_consult';
    public $table_pk = 'consult_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 新增预约
     * @param Request $request
     * @param RulesConsult $validator
     */
    public function Add(Request $request,RulesConsult $validator)
    {
        $validator_res = $validator->Add($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $consultant_id = $all['consultant_id']; //顾问id

        //查询房源
        $where = array(
            ['consultant_id','=',$consultant_id]
        );
        $house_info = DB::table('web_store_consultant')
            ->select('consultant_id as id','is_delete')
            ->where($where)
            ->first();
        if(empty($house_info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到该顾问');
        }
        elseif($house_info->is_delete == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该顾问已删除');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $insert_data = array(
                'user_id' => $request->user_info->user_id,
                'consultant_id' => $consultant_id,
                'create_time' => now(),
                'create_user_id' => $request->user_info->user_id,
                'update_time' => now(),
                'update_user_id' => $request->user_info->user_id,
            );
            DB::table($this->table_name)
                ->insert($insert_data);

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'咨询失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'咨询成功');
    }


    /**
     * 分页列表
     * @param Request $request
     * @param RulesConsult $validator
     */
    public function GetPage(Request $request,RulesConsult $validator)
    {
        $validator_res = $validator->GetPage($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $limit = isset($all['limit']) ? $all['limit'] : 10; //每页数量

        //默认条件
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.user_id','=',$request->user_info->user_id],
        );

        $data = DB::table($this->table_name)
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.consultant_id',$this->table_name.'.create_time','web_store_consultant.consultant_name','web_store_consultant.head as consultant_head','web_store_consultant.phone as consultant_phone')
            ->leftJoin('web_store_consultant',$this->table_name.'.consultant_id','=','web_store_consultant.consultant_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);


        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$data->total(),$data->lastPage());
    }

}
