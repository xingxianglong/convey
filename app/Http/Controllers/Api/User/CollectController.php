<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\House\LabelMappingController;
use App\Rules\Api\User\Collect as RulesCollect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectController extends BaseController
{
    public $table_name = 'web_user_collect';
    public $table_pk = 'collect_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 获取数据
     * @param Request $request
     * @param RulesCollect $validator
     */
    public function Update(Request $request,RulesCollect $validator)
    {
        $validator_res = $validator->Update($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $house_id = $all['house_id']; //房源id

        //查询房源
        $where = array(
            ['house_id','=',$house_id]
        );
        $house_info = DB::table('web_house')
            ->select('house_id as id','is_show','is_delete')
            ->where($where)
            ->first();
        if(empty($house_info))
        {
            return $this->common_class->ajaxDataReturnFormat(1,'找不到该房源');
        }
        elseif($house_info->is_show != 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该房源已下架');
        }
        elseif($house_info->is_delete == 1)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'该房源已删除');
        }


        // 启动事务
        Db::beginTransaction();
        try {
            $where = array(
                [$this->table_name.'.house_id','=',$house_id],
                [$this->table_name.'.user_id','=',$request->user_info->user_id],
            );
            $info = DB::table($this->table_name)
                ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.is_delete')
                ->where($where)
                ->first();

            if(empty($info))
            {
                $msg = '收藏';
                $insert_data = array(
                    'user_id' => $request->user_info->user_id,
                    'house_id' => $house_id,
                    'create_time' => now(),
                    'create_user_id' => $request->user_info->user_id,
                    'update_time' => now(),
                    'update_user_id' => $request->user_info->user_id,
                );
                DB::table($this->table_name)
                    ->insert($insert_data);
            }
            else
            {
                if($info->is_delete == 1)
                {
                    $msg = '收藏';
                    $update_data = array(
                        'is_delete' => 0,
                        'delete_time' => null,
                        'delete_ps' => '',
                        'delete_user_id' => 0,
                        'update_time' => now(),
                        'update_user_id' => $request->user_info->user_id,
                    );
                }
                else
                {
                    $msg = '取消';
                    $delete_ps = '用户取消收藏';
                    $update_data = array(
                        'is_delete' => 1,
                        'delete_time' => now(),
                        'delete_ps' => $delete_ps,
                        'delete_user_id' => $request->user_info->user_id,
                    );
                }
                $where = array(
                    [$this->table_pk,'=',$info->id]
                );
                DB::table($this->table_name)
                    ->where($where)
                    ->update($update_data);
            }

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,$msg.'失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,$msg.'成功');
    }


    /**
     * 分页列表
     * @param Request $request
     * @param RulesCollect $validator
     */
    public function GetPage(Request $request,RulesCollect $validator)
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
            ->select($this->table_name.'.'.$this->table_pk.' as id',$this->table_name.'.house_id','web_house.title','web_house.cover','web_house.is_optimization','web_house.rent','web_house.acreage','web_house.room','web_house.hall','web_house.toilet','web_house.entire_or_joint')
            ->leftJoin('web_house',$this->table_name.'.house_id','=','web_house.house_id')
            ->where($where)
            ->orderBy($this->table_name.'.'.$this->table_pk,'desc')
            ->paginate($limit);

        $label_mapping = new LabelMappingController($request);
        foreach($data as $k => $v)
        {
            //标签
            $lavel_res = $label_mapping->AccordingHouseGetData($v->house_id,2);
            $data[$k]->label_data = $lavel_res['data'];
        }

        return $this->common_class->ajaxDataReturnFormat(0,'查询成功',$data->items(),$data->total(),$data->lastPage());
    }

}
